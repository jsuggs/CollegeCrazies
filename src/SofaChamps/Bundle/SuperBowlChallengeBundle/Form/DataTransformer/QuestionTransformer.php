<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

class QuestionTransformer implements DataTransformerInterface
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
     * Transforms an object (Question) to a string (id).
     *
     * @param  Question|null $question
     * @return string
     */
    public function transform($question)
    {
        if (null === $question) {
            return "";
        }

        return $question->getId();
    }

    /**
     * Transforms a string (id) to an object (Question).
     *
     * @param  string $id
     * @return Question|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $question = $this->om
            ->getRepository('SofaChampsSuperBowlChallengeBundle:Question')
            ->findOneBy(array('id' => $id));

        if (!$question) {
            throw new TransformationFailedException(sprintf('An question with id "%s" does not exist!', $id));
        }

        return $question;
    }
}
