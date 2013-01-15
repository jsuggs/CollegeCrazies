<?php

namespace SofaChamps\Bundle\QuestionBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Multiple Choice Question component
 *
 * @ORM\Entity
 */
class MultipleChoiceComponent extends AbstractQuestionComponent
{
    /**
     * @ORM\OneToMany(targetEntity="QuestionComponentChoice", mappedBy="component", cascade={"persist"}, orphanRemoval=true)
     */
    protected $choices;

    public function __construct()
    {
        $this->choices = new ArrayCollection();
    }

    public function addChoice(QuestionComponentChoice $choice)
    {
        if (!$this->choices->contains($choice)) {
            $choice->setQuestion($this);
            $this->choices[] = $choice;
        }
    }

    public function removeChoice(QuestionComponentChoice $choice)
    {
        if (!$this->choices->removeElement($choice)) {
            throw new \InvalidArgumentException('Unable to delete choice');
        }
    }

    public function getChoices()
    {
        return $this->choices;
    }

    public function getResponseType()
    {
        return 'multiple';
    }
}
