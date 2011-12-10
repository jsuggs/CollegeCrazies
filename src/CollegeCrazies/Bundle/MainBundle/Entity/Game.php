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
     * @ORM\Column
     */
    protected $id;

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
     * @var DateTime
     */
    protected $date;

    /**
     * Where the game is played
     *
     * @ORM\Column(type="string", length="255")
     * @var string
     */
    protected $location;

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
}
