<?php

namespace SofaChamps\Bundle\CoreBundle\Request\ParamConverter;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * UserParamConverter
 *
 * @DI\Service("sofachamps.param_converter.user")
 * @DI\Tag("request.param_converter", attributes={"priority"=1})
 */
class UserParamConverter implements ParamConverterInterface
{
    protected $em;

    /**
     * @DI\InjectParams({
     *      "em" = @DI\Inject("doctrine.orm.default_entity_manager")
     * })
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function apply(Request $request, ConfigurationInterface $configuration)
    {
        $options = $configuration->getOptions();
        $param = array_key_exists('id', $options) ? $options['id'] : 'userId';
        $userId = $request->attributes->get($param);

        $user = $this->em->getRepository('SofaChampsCoreBundle:User')->find($userId);
        if (!$user) {
            throw new NotFoundHttpException(sprintf('User with id: %s could not be found', $userId));
        }

        $request->attributes->set($configuration->getName(), $user);
    }

    public function supports(ConfigurationInterface $configuration)
    {
        return in_array($configuration->getClass(), array('SofaChamps\Bundle\CoreBundle\Entity\User', ''));
    }
}
