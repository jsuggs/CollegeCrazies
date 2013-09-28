<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * @DI\Service("transformer.pick")
 */
class PickTransformer implements DataTransformerInterface
{
    private $om;

    /**
     * @DI\InjectParams({
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager")
     * })
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (Pick) to a string (id).
     *
     * @param  Pick|null $pick
     * @return string
     */
    public function transform($pick)
    {
        if (null === $pick) {
            return "";
        }

        return $pick->getId();
    }

    /**
     * Transforms a string (id) to an object (Pick).
     *
     * @param  string $id
     * @return Pick|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $pick = $this->om
            ->getRepository('SofaChampsSuperBowlChallengeBundle:Pick')
            ->findOneBy(array('id' => $id));

        if (!$pick) {
            throw new TransformationFailedException(sprintf('An pick with id "%s" does not exist!', $id));
        }

        return $pick;
    }
}
