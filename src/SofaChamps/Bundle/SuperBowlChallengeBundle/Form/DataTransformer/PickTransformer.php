<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

class PickTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
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
