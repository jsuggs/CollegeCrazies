<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $user;

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

    public function __construct(User $user, PredictionSet $predictionSet, PickSet $pickSet)
    {
        $this->user = $user;
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
}
