<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Season and assocated metadata
 *
 * @ORM\Entity
 * @ORM\Table(name="bp_seasons")
 */
class Season
{
    /**
     * The year of the season
     *
     * @ORM\Id
     * @ORM\Column(name="season", type="integer", length=4)
     * @ORM\GeneratedValue(strategy="NONE")
     * @Assert\Range(min=2012, max=2020)
     */
    protected $season;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $hasChampionship;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $picksLockAt;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $locked;

    /**
     * @ORM\Column(type="integer")
     */
    protected $gamePoints;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $championshipPoints;

    /**
     * @ORM\ManyToOne(targetEntity="SofaChamps\Bundle\NCAABundle\Entity\Team")
     * @ORM\JoinColumn(name="champ_team_id", referencedColumnName="id")
     */
    protected $championshipWinner;

    public function __construct($season)
    {
        $this->season = $season;
    }

    public function setSeason($season)
    {
        $this->season = $season;
    }

    public function getSeason()
    {
        return $this->season;
    }

    public function setHasChampionship($hasChampionship)
    {
        $this->hasChampionship = $hasChampionship;
    }

    public function getHasChampionship()
    {
        return $this->hasChampionship;
    }

    public function getPossiblePoints()
    {
        return $this->gamePoints + $this->getChampionshipPoints();
    }

    public function getChampionshipPoints()
    {
        return $this->championshipPoints ?: 0;
    }

    public function setGamePoints($gamePoints)
    {
        $this->gamePoints = $gamePoints;
    }

    public function getGamePoints()
    {
        return $this->gamePoints;
    }

    public function isLocked()
    {
        return (bool) $this->locked;
    }

    public function setLocked($locked)
    {
        $this->locked = $locked;
    }

    public function setPicksLockAt($picksLockAt)
    {
        $this->picksLockAt = $picksLockAt;
    }

    public function getPicksLockAt()
    {
        return $this->picksLockAt;
    }

    public function __toString()
    {
        return (string)$this->season ?: 'Unknown';
    }

    public function setChampionshipWinner($team)
    {
        $this->championshipWinner = $team;
    }

    public function getChampionshipWinner()
    {
        return $this->championshipWinner;
    }
}
