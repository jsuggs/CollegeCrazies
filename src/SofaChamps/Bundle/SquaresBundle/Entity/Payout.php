<?php

namespace SofaChamps\Bundle\SquaresBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Payout for the game
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="squares_game_payouts"
 * )
 */
class Payout
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_squares_payout", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="payouts")
     */
    protected $game;

    /**
     * @ORM\Column
     */
    protected $description;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $percentage;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $homeTeamResult;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $awayTeamResult;

    /**
     * @ORM\ManyToOne(targetEntity="Player", inversedBy="winners")
     */
    protected $winner;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function getGame()
    {
        return $this->game;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;
    }

    public function getPercentage()
    {
        return $this->percentage;
    }

    public function setHomeTeamResult($result)
    {
        $this->homeTeamResult = $result;
    }

    public function getHomeTeamResult()
    {
        return $this->homeTeamResult;
    }

    public function setAwayTeamResult($result)
    {
        $this->awayTeamResult = $result;
    }

    public function getAwayTeamResult()
    {
        return $this->awayTeamResult;
    }

    public function setWinner(Player $player = null)
    {
        $this->winner = $player;
    }

    public function getWinner()
    {
        return $this->winner;
    }

    public function isComplete()
    {
        return isset($this->homeTeamResult) && isset($this->awayTeamResult);
    }

    public function getRowResult()
    {
        return substr((string) $this->homeTeamResult, -1);
    }

    public function getColResult()
    {
        return substr((string) $this->awayTeamResult, -1);
    }
}
