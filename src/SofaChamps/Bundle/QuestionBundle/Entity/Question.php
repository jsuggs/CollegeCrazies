<?php

namespace SofaChamps\Bundle\QuestionBundle\Entity;

use CollegeCrazies\Bundle\MainBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\OneToMany(targetEntity="QuestionChoice", mappedBy="question", cascade={"persist"}, orphanRemoval=true)
     */
    protected $choices;

    public function __construct()
    {
        $this->choices = new ArrayCollection();
    }

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
        if (!$this->choices->contains($choice)) {
            $choice->setQuestion($this);
            $this->choices[] = $choice;
        }
    }

    public function removeChoice(QuestionChoice $choice)
    {
        if (!$this->choices->removeElement($choice)) {
            throw new \InvalidArgumentException('Unable to delete choice');
        }
    }

    public function getChoices()
    {
        return $this->choices;
    }
}
