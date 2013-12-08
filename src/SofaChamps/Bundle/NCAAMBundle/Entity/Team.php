<?php

namespace SofaChamps\Bundle\NCAAMBundle\Entity;

use SofaChamps\Bundle\BasketballBundle\Entity\BasketballTeam;

/**
 * A basketball team
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="ncaam_teams",
 * )
 */
class Team extends BasketballTeam
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $year;
}
