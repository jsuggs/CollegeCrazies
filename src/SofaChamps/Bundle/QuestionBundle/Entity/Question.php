<?php

namespace SofaChamps\Bundle\QuestionBundle\Entity;

use CollegeCrazies\Bundle\MainBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Question
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="question_questions"
 * )
 */
class Question
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_question_question", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * The text of the question
     *
     * @ORM\Column(type="string")
     * @var string
     */
    protected $text;

    /**
     * @ORM\OneToMany(targetEntity="QuestionChoice", mappedBy="question")
     */
    protected $choices;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getText()
    {
        return $this->text;
    }

    public function addChoice(QuestionChoice $choice)
    {
        $this->choices[] = $choice;
    }

    public function getChoices()
    {
        return $this->choices;
    }
}
