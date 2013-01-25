<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Super Bowl Challenge Bonus Question
 * There are 4 questions per year
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="sbc_questions"
 * )
 */
class Question
{
    /**
     * The year for this pick
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $year;

    /**
     * The index/order for the pick (1-4)
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @Assert\Range(min=1, max=4, invalidMessage="Index range is 1-4")
     */
    protected $index;

    /**
     * Config the question belongs to
     *
     * @ORM\ManyToOne(targetEntity="Config", inversedBy="questions")
     * @ORM\JoinColumn(name="year", referencedColumnName="year")
     */
    protected $config;

    /**
     * The text of the question
     *
     * @ORM\Column(type="string")
     */
    protected $text;

    /**
     * Choices
     *
     * @ORM\OneToMany(targetEntity="QuestionChoice", mappedBy="question", cascade={"persist"})
     */
    protected $choices;

    public function setYear($year)
    {
        $this->year = $year;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function setIndex($index)
    {
        if ($index < 1 || $index > 4) {
            throw new \RangeException('Index range 1-4');
        }

        $this->index = $index;
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function setConfig(Config $config)
    {
        $this->config = $config;
    }

    public function getConfig()
    {
        return $this->config;
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
        $choice->setQuestion($this);
        $this->choices[] = $choice;
    }

    public function removeChoice(QuestionChoice $choice)
    {
        $this->choices->removeElement($choice);
    }

    public function getChoices()
    {
        return $this->choices;
    }
}
