<?php

namespace SofaChamps\Bundle\NCAABundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\AbstractTeam;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A ncaa mens football team
 *
 * @ORM\Entity
 */
class NCAAFTeam extends Team
{
    public $conference;
}
