<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A set of predictions
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="prediction_sets"
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
     * @ORM\OneToMany(targetEntity="Prediction", mappedBy="predictionSet")
     */
    protected $predictions;

    public function getId()
    {
        return $this->id;
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
