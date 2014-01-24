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
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $id;

    /**
     * The sequence the payouts happen
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $seq;

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

    /**
     * @ORM\Column(type="boolean")
     */
    protected $carryover = false;

    public function __construct(Game $game, $seq)
    {
        $this->game = $game;
        $this->id = $game->getId();
        $this->seq = $seq;
    }

    public function getGame()
    {
        return $this->game;
    }

    public function setSeq($seq)
    {
        $this->seq = $seq;
    }

    public function getSeq()
    {
        return $this->seq;
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

    public function getPercentage($percent = false)
    {
        return $percent
            ? $this->percentage / 100
            : $this->percentage;
    }

    public function incrementPercentage($percentage)
    {
        $this->percentage += $percentage;
    }

    public function getPayoutAmount($dollars = false)
    {
        return $this->game->getTotalPayoutAmount($dollars) * $this->getPercentage(true);
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

    public function setCarryover($carryover)
    {
        $this->carryover = (bool) $carryover;
    }

    public function isCarryover()
    {
        return $this->carryover;
    }
}
