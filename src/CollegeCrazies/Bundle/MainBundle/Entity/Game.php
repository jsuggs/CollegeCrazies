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
class Game {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="game_seq", initialValue=1, allocationSize=100)
     */
    protected $id;

    /**
     * The Bowl Game Name
     * @ORM\Column(type="string", length="255")
     *
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
     * season
     *
     * @var string
     */
    protected $season;

    /**
     * Gametime baby
     *
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    protected $gameDate;

    /**
     * Where the game is played
     *
     * @ORM\Column(type="string", length="255")
     * @var string
     */
    protected $location;

    /**
     * network
     *
     * @ORM\Column(type="string", length="255")
     * @var string
     */
    protected $network;

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

    public function getName()
    {
        return $this->name;
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

    public function getLocation()
    {
        return $this->location;
    }
}
