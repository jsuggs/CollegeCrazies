<?php

namespace SofaChamps\Bundle\QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Question Component
 *
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="responseType", type="string")
 * @ORM\DiscriminatorMap({
 *     "multiple-choice" = "MultipleChoiceComponent",
 *     "text" = "TextComponent"
 * })
 * @ORM\Table(
 *      name="question_question_components"
 * )
 */
abstract class AbstractQuestionComponent
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_question_question_component", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Question", inversedBy="components")
     */
    protected $question;

    /**
     * The text of the component
     *
     * @ORM\Column(type="string", nullable=true)
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

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getText()
    {
        return $this->text;
    }

    abstract public function getResponseType();
}
