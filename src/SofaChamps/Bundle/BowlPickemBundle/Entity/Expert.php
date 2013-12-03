<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\User;

/**
 * An expert to make an expert pick
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="bp_experts"
 * )
 */
class Expert
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_experts", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * Expert Name
     *
     * @ORM\Column(type="string")
     * @var string
     */
    protected $name;

    /**
     * Expert Picks made by this expert
     *
     * @ORM\OneToMany(targetEntity="ExpertPick", mappedBy="expert")
     */
    protected $expertPicks;

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function addExpertPick(ExpertPick $expertPick)
    {
        $this->expertPicks->add($expertPick);
    }

    public function getExpertPicks()
    {
        return $this->expertPicks;
    }

    public function __toString()
    {
        return $this->id ? $this->name : 'New Expert';
    }
}
