<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A set of predictions
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="prediction_sets",
 *      indexes={@ORM\Index(name="IDX_PREDICTION_SETS_SEASON", columns={"season"})}
 * )
 */
class PredictionSet
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_prediction_set", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Season")
     * @ORM\JoinColumn(name="season", referencedColumnName="season")
     */
    protected $season;

    /**
     * @ORM\OneToMany(targetEntity="Prediction", mappedBy="predictionSet")
     */
    protected $predictions;

    public function getId()
    {
        return $this->id;
    }

    public function setSeason(Season $season)
    {
        $this->season = $season;
    }

    public function getSeason()
    {
        return $this->season;
    }

    public function getPredictions()
    {
        return $this->predictions;
    }

    public function setPredictions($predictions)
    {
        $this->predictions = $predictions;
    }

    public function findPredictionByGame(Game $game)
    {
        foreach ($this->predictions as $prediction) {
            if ($prediction->getGame() == $game) {
                return $prediction;
            }
        }
    }
}
