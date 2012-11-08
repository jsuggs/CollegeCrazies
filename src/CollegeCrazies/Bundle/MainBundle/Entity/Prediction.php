<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * The metadata for a league
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="predictions"
 * )
 */
class Prediction
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PredictionSet", inversedBy="predictions")
     */
    protected $predictionSet;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="predictions")
     */
    protected $game;

    /**
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="predictions")
     */
    protected $winner;

    /**
     * homeTeamScore
     *
     * @ORM\Column(type="integer")
     * @var integer
     */
    protected $homeTeamScore;

    /**
     * awayTeamScore
     *
     * @ORM\Column(type="integer")
     * @var integer
     */
    protected $awayTeamScore;

    public function __construct(PredictionSet $set, Game $game, Team $winner)
    {
        $this->predictionSet = $set;
        $this->game = $game;
        $this->winner = $winner;
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

    public function getGame()
    {
        return $this->game;
    }
}
