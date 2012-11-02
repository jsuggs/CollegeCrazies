<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use CollegeCrazies\Bundle\MainBundle\Entity\Team;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Game
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="games"
 * )
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_game", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * The Bowl Game Name
     *
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    protected $name;

    /**
     * @ORM\OneToOne(targetEntity="Team")
     */
    protected $homeTeam;

    /**
     * @ORM\OneToOne(targetEntity="Team")
     */
    protected $awayTeam;

    /**
     * Gametime baby
     *
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    protected $gameDate;

    /**
     * TV Network
     *
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    protected $network;

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
     * description
     *
     * @ORM\Column(type="text", nullable=true)
     * @var string
     */
    protected $description;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setHomeTeam(Team $team)
    {
        $this->homeTeam = $team;
    }

    public function getHomeTeam()
    {
        return $this->homeTeam;
    }

    public function getAwayTeam()
    {
        return $this->awayTeam;
    }

    public function setAwayTeam(Team $team)
    {
        $this->awayTeam = $team;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setGameDate($date)
    {
        $this->gameDate = $date;
    }

    public function getGameDate()
    {
        return $this->gameDate;
    }

    public function setNetwork($network)
    {
        $this->network = $network;
    }

    public function getNetwork()
    {
        return $this->network;
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

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function isComplete()
    {
        return (isset($this->homeTeamScore) && isset($this->awayTeamScore));
    }

    public function getWinner()
    {
        if (!$this->isComplete()) {
            return null;
        }

        return ($this->homeTeamScore > $this->awayTeamScore) ? $this->homeTeam : $this->awayTeam;
    }
}
