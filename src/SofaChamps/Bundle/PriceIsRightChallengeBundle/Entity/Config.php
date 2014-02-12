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

    public function getCostForSeed($seed)
    {
        return $this->{sprintf('seed%dCost', $seed)};
    }

    public function getWinForRound($round)
    {
        return $this->{sprintf('round%dWin', $round)};
    }
}
