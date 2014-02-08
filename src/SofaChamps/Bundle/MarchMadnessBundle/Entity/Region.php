<?php

namespace SofaChamps\Bundle\MarchMadnessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\BracketBundle\Entity\AbstractBracket;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The regions for a bracket
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="mm_regions"
 * )
 */
class Region
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Bracket", inversedBy="regions")
     * @ORM\JoinColumn(name="season", referencedColumnName="season")
     */
    protected $bracket;

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=8)
     */
    protected $abbr;

    /**
     * @ORM\Column(type="string", length=32)
     */
    protected $name;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $index;

    /**
     * @ORM\OneToMany(targetEntity="BracketTeam", mappedBy="region")
     */
    protected $teams;

    public function __construct(Bracket $bracket)
    {
        $this->bracket = $bracket;
    }

    public function getBracket()
    {
        return $this->season;
    }
}
