<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Request\ParamConverter;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @DI\Service
 * @DI\Tag("request.param_converter", attributes={"converter"="season", "priority"="25"})
 */
class SeasonParamConverter implements ParamConverterInterface
{
    private $em;

    /**
     * @DI\InjectParams({
     *     "em" = @DI\Inject("doctrine.orm.default_entity_manager")
     * })
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function apply(Request $request, ConfigurationInterface $configuration)
    {
        if ($season = $request->attributes->get('season')) {
            $seasonObj = $this->em->getRepository('SofaChampsBowlPickemBundle:Season')->find($season);

            if (!$season) {
                throw new NotFoundHttpException();
            }
            $param = $configuration->getName();
            $request->attributes->set($param, $seasonObj);
        } else {
            throw new NotFoundHttpException();
        }

        return true;
    }

    public function supports(ConfigurationInterface $configuration)
    {
        return "SofaChamps\Bundle\BowlPickemBundle\Entity\Season" === $configuration->getClass();
    }
}
