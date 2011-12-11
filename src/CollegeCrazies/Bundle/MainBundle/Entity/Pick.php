<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use CollegeCrazies\Bundle\MainBundle\Entity\Team;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Users Pick for a single game
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="picks"
 * )
 */
class Pick {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="pick_seq", initialValue=1, allocationSize=100)
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="User")
     */
    protected $user;

    protected $league;

    /**
     * @ORM\OneToOne(targetEntity="Game")
     */
    protected $game;

    /**
     * @ORM\OneToOne(targetEntity="Team")
     */
    protected $team;

    /**
     * @ORM\Column(type="integer")
     */
    protected $confidence;

    public function getId()
    {
        return $this->id;
    }

    public function setTeam(Team $team)
    {
        if ($team != $game->getHomeTeam() || $team != $game->getAwayTeam()) {
            die('bad');
        }
        $this->team = $team;
    }

    public function getTeam()
    {
        return $this->team;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setGame(Game $game)
    {
        $this->game = $game;
    }

    public function getGame()
    {
        return $this->game;
    }

    public function setConfidence($confidence)
    {
        $this->confidence = $confidence;
    }

    public function getConfidence()
    {
        return $this->confidence;
    }

    public function __toString()
    {
        return 'AAA';
    }
}
