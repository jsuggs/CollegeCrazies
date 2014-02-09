<?php

namespace SofaChamps\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbstractGame
 *
 * This is a baseline for the construct of a game
 * However, you must define the relationship for the id and the teams
 *
 * TODO - Move the spread, overunder and any additional meta to a new core class
 */
abstract class AbstractGame implements GameInterface
{
    protected $homeTeam;
    protected $awayTeam;

    /**
     * homeTeamScore
     *
     * @ORM\Column(type="integer", nullable=true)
     * @var integer
     */
    protected $homeTeamScore;

    /**
     * awayTeamScore
     *
     * @ORM\Column(type="integer", nullable=true)
     * @var integer
     */
    protected $awayTeamScore;

    /**
     * Gametime baby
     *
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    protected $gameDate;

    /**
     * Bowl location
     *
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    protected $location;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setHomeTeam(TeamInterface $team)
    {
        $this->homeTeam = $team;
    }

    public function getHomeTeam()
    {
        return $this->homeTeam;
    }

    public function setAwayTeam(TeamInterface $team)
    {
        $this->awayTeam = $team;
    }

    public function getAwayTeam()
    {
        return $this->awayTeam;
    }

    public function setHomeTeamScore($score)
    {
        $this->homeTeamScore = $score;
    }

    public function getHomeTeamScore()
    {
        return $this->homeTeamScore;
    }

    public function setAwayTeamScore($score)
    {
        $this->awayTeamScore = $score;
    }

    public function getAwayTeamScore()
    {
        return $this->awayTeamScore;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setGameDate($date)
    {
        $this->gameDate = $date;
    }

    public function getGameDate()
    {
        return $this->gameDate;
    }

    public function getWinner()
    {
        if (!$this->isComplete()) {
            return null;
        }

        return $this->homeTeamScore > $this->awayTeamScore
            ? $this->homeTeam
            : $this->awayTeam;
    }

    public function isComplete()
    {
        return (isset($this->homeTeamScore) && isset($this->awayTeamScore));
    }
}
