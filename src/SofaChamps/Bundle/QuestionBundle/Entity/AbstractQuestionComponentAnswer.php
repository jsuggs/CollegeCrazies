<?php

namespace SofaChamps\Bundle\QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Question component answer
 *
 * ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *     "multiple-choice" = "MultipleChoiceQuestionChoice",
 *     "text" = "TextQuestionChoice"
 * })
 * @ORM\Table(
 *      name="question_question_answer"
 * )
 */
abstract class AbstractQuestionComponentAnswer
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_question_question_choice", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AbstractQuestion", inversedBy="choices")
     */
    protected $question;

    /**
     * The text of the choice
     *
     * @ORM\Column(type="string")
     * @var string
     */
    protected $text;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setQuestion(Question $question = null)
    {
        $this->question = $question;
    }

    public function getQuestion()
    {
        return $this->question;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getText()
    {
        return $this->text;
    }

    abstract public function getType();
}
