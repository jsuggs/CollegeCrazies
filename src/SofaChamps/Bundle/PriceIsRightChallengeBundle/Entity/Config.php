<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The config for a game
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="pirc_config"
 * )
 */
class Config
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_pirc_config", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Game", inversedBy="config")
     */
    protected $game;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(min=0, max=100)
     */
    protected $seed1Cost = 25;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(min=0, max=100)
     */
    protected $seed2Cost = 15;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(min=0, max=100)
     */
    protected $seed3Cost = 10;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(min=0, max=100)
     */
    protected $seed4Cost = 8;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(min=0, max=100)
     */
    protected $seed5Cost = 6;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(min=0, max=100)
     */
    protected $seed6Cost = 5;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(min=0, max=100)
     */
    protected $seed7Cost = 5;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(min=0, max=100)
     */
    protected $seed8Cost = 4;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(min=0, max=100)
     */
    protected $seed9Cost = 4;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(min=0, max=100)
     */
    protected $seed10Cost = 3;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(min=0, max=100)
     */
    protected $seed11Cost = 3;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(min=0, max=100)
     */
    protected $seed12Cost = 2;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(min=0, max=100)
     */
    protected $seed13Cost = 2;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(min=0, max=100)
     */
    protected $seed14Cost = 1;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(min=0, max=100)
     */
    protected $seed15Cost = 1;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(min=0, max=100)
     */
    protected $seed16Cost = 1;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $round1Win = 10;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $round2Win = 20;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $round3Win = 30;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $round4Win = 40;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $round5Win = 80;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $round6Win = 100;

    public function setGame(Game $game)
    {
        $this->game = $game;
    }

    public function getCostForSeed($seed)
    {
        return $this->{sprintf('seed%dCost', $seed)};
    }

    public function getWinForRound($round)
    {
        return $this->{sprintf('round%dWin', $round)};
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}
