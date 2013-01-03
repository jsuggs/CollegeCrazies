<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Super Bowl Challenge Picks
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="sbc_picks"
 * )
 */
class Pick
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_sbc_pick", initialValue=1, allocationSize=1)
     */
    protected $id;
}
