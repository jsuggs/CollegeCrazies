<?php

namespace SofaChamps\Bundle\NCAAMBundle\Entity;

use SofaChamps\Bundle\BasketballBundle\Entity\BasketballTeam;

/**
 * A basketball team
 */
class Team extends BasketballTeam
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $year;
}
