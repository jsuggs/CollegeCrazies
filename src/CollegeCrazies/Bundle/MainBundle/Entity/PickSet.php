<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use CollegeCrazies\Bundle\MainBundle\Entity\User;
use CollegeCrazies\Bundle\MainBundle\Entity\League;
use CollegeCrazies\Bundle\MainBundle\Entity\Pick;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Users Pick for a single game
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="picksets"
 * )
 */
class PickSet {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="pickset_seq", initialValue=1, allocationSize=100)
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="Pick", mappedBy="pickSet")
     */
    protected $picks;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $tiebreakerHomeTeamScore;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $tiebreakerAwayTeamScore;

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setPicks($picks)
    {
        $this->picks = $picks;
    }

    public function addPick(Pick $pick)
    {
        $this->picks[] = $pick;
    }

    public function getPicks()
    {
        return $this->picks;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getTiebreakerHomeTeamScore()
    {
        return $this->tiebreakerHomeTeamScore;
    }

    public function setTiebreakerHomeTeamScore($score)
    {
        $this->tiebreakerHomeTeamScore = $score;
    }

    public function getTiebreakerAwayTeamScore()
    {
        return $this->tiebreakerAwayTeamScore;
    }

    public function setTiebreakerAwayTeamScore($score)
    {
        $this->tiebreakerAwayTeamScore = $score;
    }

    public function getGames()
    {
        $games = array();
        foreach ($this->picks as $pick) {
            $games[] = $pick->getGame();
        }
        return $games;
    }

    public function getCompletedGames()
    {
        $completedGames = array();
        foreach ($this->getGames() as $game) {
            if ($game->isComplete()) {
                $completedGames[] = $game;
            }
        }
        return $completedGames;
    }

    public function getPoints()
    {
        $points = 0;
        foreach ($this->getWins() as $win) {
            $points += $win->getConfidence();
        }
        return $points;
    }

    public function getWins()
    {
        $wins = array();
        foreach ($this->picks as $pick) {
            if ($pick->getGame()->isComplete() && $pick->getTeam() == $pick->getGame()->getWinner()) {
                $wins[] = $pick;
            }
        }
        return $wins;
    }

    public function getLoses()
    {
        $loses = array();
        foreach ($this->picks as $pick) {
            if ($pick->getGame()->isComplete() && $pick->getTeam() != $pick->getGame()->getWinner()) {
                $loses[] = $pick;
            }
        }
        return $loses;
    }

    public function getPointsPossible()
    {
        $pointsPossible = 630;
        foreach ($this->getLoses() as $loss) {
            $pointsPossible -= $loss->getConfidence();
        }
        return $pointsPossible;
    }

    /**
     * Needs to be refactored
     */
    public function findPickByGame(Game $game)
    {
        foreach ($this->picks as $pick) {
            if ($pick->getGame() == $game) {
                return $pick;
            }
        }
    }
}
