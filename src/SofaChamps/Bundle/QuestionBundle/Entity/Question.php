<?php

namespace SofaChamps\Bundle\QuestionBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Question
 * A question is made up of one or more components
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
     * @ORM\OneToMany(targetEntity="AbstractQuestionComponent", mappedBy="question", cascade={"persist"}, orphanRemoval=true)
     */
    protected $components;

    public function __construct()
    {
        $this->components = new ArrayCollection();
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

    public function addComponent(QuestionComponent $component)
    {
        if (!$this->components->contains($component)) {
            $components->setQuestion($this);
            $this->components[] = $component;
        }
    }

    public function removeComponent(QuestionComponent $component)
    {
        if (!$this->components->removeElement($component)) {
            throw new \InvalidArgumentException('Unable to delete component');
        }
    }

    public function getComponents()
    {
        return $this->components;
    }
}
