<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use CollegeCrazies\Bundle\MainBundle\Entity\League;
use CollegeCrazies\Bundle\MainBundle\Entity\Pick;
use CollegeCrazies\Bundle\MainBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A collection of picks for a given leage
 *
 * @ORM\Entity(repositoryClass="PickSetRepository")
 * @ORM\Table(
 *      name="picksets"
 * )
 */
class PickSet
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_pickset", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank()
     * @Assert\MaxLength(limit=40)
     */
    protected $name;

    /**
     * @ORM\ManyToMany(targetEntity="League", mappedBy="pickSets")
     */
    protected $leagues;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="pickSets")
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="Pick", mappedBy="pickSet", cascade={"persist"})
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

    /**
     * @ORM\OneToMany(targetEntity="UserPredictionSetScore", mappedBy="pickSet")
     */
    protected $predictionScores;

    public function __construct()
    {
        $this->leagues = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getPicks()
    {
        return $this->picks;
    }

    public function addPick(Pick $pick)
    {
        $this->picks[] = $pick;
        $pick->setPickSet($this);
    }

    public function removePick(Pick $pick)
    {
        $this->picks->remove($pick);
    }

    public function setPicks($picks)
    {
        $this->picks = $picks;
    }

    public function getLeagues()
    {
        return $this->leagues;
    }

    public function addLeague(League $league)
    {
        $league->addPickSet($this);
        $this->leagues[] = $league;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function setTiebreakerHomeTeamScore($score)
    {
        $this->tiebreakerHomeTeamScore = $score;
    }

    public function getTiebreakerHomeTeamScore()
    {
        return $this->tiebreakerHomeTeamScore;
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

    public function getUserPredictionSetScoreForPredictionSet(PredictionSet $predictionSet)
    {
        foreach ($this->predictionScores as $predictionScore) {
            if ($predictionScore->getPredictionSet() == $predictionSet) {
                return $predictionScore;
            }
        }
    }

    public function getAveragePredictionScore()
    {
        $scores = $this->getPredictionScores();
        return array_sum($scores) / count($scores);
    }

    public function getHighestPredictionScore()
    {
        $scores = $this->getPredictionScores();
        return max($scores);
    }

    public function getLowestPredictionScore()
    {
        $scores = $this->getPredictionScores();
        return min($scores);
    }

    public function getAveragePredictionFinish()
    {
        $finishes = $this->getPredictionFinishes();
        return array_sum($finishes) / count($finishes);
    }

    public function getHigestPredictionFinish()
    {
        $finishes = $this->getPredictionFinishes();
        return min($finishes);
    }

    public function getLowestPredictionFinish()
    {
        $finishes = $this->getPredictionFinishes();
        return max($finishes);
    }

    protected function getPredictionScores()
    {
        return array_map(function (UserPredictionSetScore $setScore) {
            return $setScore->getScore();
        }, $this->predictionScores->toArray());
    }

    protected function getPredictionFinishes()
    {
        return array_map(function (UserPredictionSetScore $setScore) {
            return $setScore->getFinish();
        }, $this->predictionScores->toArray());
    }
}
