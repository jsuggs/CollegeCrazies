<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The config for a game
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="pirc_config"
 * )
 */
class Config
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $id;

    public function getPointsForRound($round)
    {
        return 5;
    }
}
