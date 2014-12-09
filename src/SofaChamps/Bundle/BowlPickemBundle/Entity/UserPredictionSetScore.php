<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The score for a user against a prediction set
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="user_prediction_set_score"
 * )
 */
class UserPredictionSetScore
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SofaChamps\Bundle\CoreBundle\Entity\User")
     */
    protected $user;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="League")
     */
    protected $league;

    /**
     * @ORM\Id
     * ORM\Column(type="integer", length=4)
     * Assert\Range(min=2012, max=2020)
     * @ORM\ManyToOne(targetEntity="Season")
     * @ORM\JoinColumn(name="season", referencedColumnName="season")
     */
    protected $season;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PredictionSet")
     */
    protected $predictionSet;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PickSet", inversedBy="predictionScores")
     */
    protected $pickSet;

    /**
     * @ORM\Column(type="integer")
     */
    protected $score;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $finish;

    public function __construct(User $user, League $league, Season $season, PredictionSet $predictionSet, PickSet $pickSet)
    {
        $this->user = $user;
        $this->league = $league;
        $this->season = $season;
        $this->predictionSet = $predictionSet;
        $this->pickSet = $pickSet;
    }

    public function setFinish($finish)
    {
        $this->finish = $finish;
    }

    public function getFinish()
    {
        return $this->finish;
    }

    public function getScore()
    {
        return $this->score;
    }

    public function getPredictionSet()
    {
        return $this->predictionSet;
    }

    public function getLeague()
    {
        return $this->league;
    }
}
