<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\AbstractTeam;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Team
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="teams"
 * )
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

    /**
     * Predictions
     *
     * @ORM\OneToMany(targetEntity="Prediction", mappedBy="winner", fetch="EXTRA_LAZY")
     */
    protected $predictions;

    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }
}
