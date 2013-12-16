<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\NCAABundle\Entity\NCAAFTeam as BaseTeam;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Team
 *
 * @ORM\Entity
 */
class Team extends BaseTeam
{
    /**
     * Predictions
     *
     * @ORM\OneToMany(targetEntity="Prediction", mappedBy="winner", fetch="EXTRA_LAZY")
     */
    protected $predictions;
}
