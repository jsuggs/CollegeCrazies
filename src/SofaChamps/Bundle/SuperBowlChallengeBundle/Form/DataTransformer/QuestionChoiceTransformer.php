<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

class QuestionChoiceTransformer implements DataTransformerInterface
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
     * Transforms an object (QuestionChoice) to a string (id).
     *
     * @param  QuestionChoice|null $choice
     * @return string
     */
    public function transform($choice)
    {
        if (null === $choice) {
            return "";
        }

        return $choice->getId();
    }

    /**
     * Transforms a string (id) to an object (QuestionChoice).
     *
     * @param  string $id
     * @return QuestionChoice|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $choice = $this->om
            ->getRepository('SofaChampsSuperBowlChallengeBundle:QuestionChoice')
            ->findOneBy(array('id' => $id));

        if (!$choice) {
            throw new TransformationFailedException(sprintf('An choice with id "%s" does not exist!', $id));
        }

        return $choice;
    }
}
