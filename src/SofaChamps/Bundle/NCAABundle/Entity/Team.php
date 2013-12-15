<?php

namespace SofaChamps\Bundle\NCAABundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\AbstractTeam;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A ncaa team
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="ncaa_teams"
 * )
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *      "bp" = "SofaChamps\Bundle\BowlPickemBundle\Entity\Team",
 *      "mm" = "SofaChamps\Bundle\MarchMadnessBundle\Entity\Team"
 * })
 */
class Team extends AbstractTeam
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=5)
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $thumbnail;

    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }
}
