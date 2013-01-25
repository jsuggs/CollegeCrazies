<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A choice for a question
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="sbc_question_choices"
 * )
 */
class QuestionChoice
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_sbc_question_choice", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $year;

    /**
     * @ORM\Column(type="integer")
     */
    protected $index;

    /**
     * The question the choice belongs to
     *
     * @ORM\ManyToOne(targetEntity="Question", inversedBy="choices")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="index", referencedColumnName="index"),
     *     @ORM\JoinColumn(name="year", referencedColumnName="year")
     * })
     */
    protected $question;

    /**
     * The text of the question
     *
     * @ORM\Column(type="string")
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

    public function setQuestion(Question $question)
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

    public function __toString()
    {
        return (string) $this->id;
    }
}
