<?php

namespace SofaChamps\Bundle\MarchMadnessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\BracketBundle\Entity\AbstractBracket;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The team in the bracket
 *
 * @ORM\Entity(repositoryClass="BracketTeamRepository")
 * @ORM\Table(
 *      name="mm_teams"
 * )
 */
class BracketTeam
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Bracket", inversedBy="teams")
     * @ORM\JoinColumn(name="season", referencedColumnName="season")
     */
    protected $bracket;

    /**
     * @ORM\ManyToOne(targetEntity="Region", inversedBy="teams")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="season", referencedColumnName="season"),
     *      @ORM\JoinColumn(name="region", referencedColumnName="abbr")
     * })
     */
    protected $region;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SofaChamps\Bundle\NCAABundle\Entity\Team", inversedBy="mmTeams")
     */
    protected $team;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $overallSeed;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $regionSeed;

    public function __construct(Bracket $bracket, Team $team, Region $region, $overallSeed, $regionSeed)
    {
        $this->bracket = $bracket;
        $this->team = $team;
        $this->region = $region;
        $this->overallSeed = $overallSeed;
        $this->regionSeed = $regionSeed;
    }

    public function getBracket()
    {
        return $this->season;
    }

    public function getTeam()
    {
        return $this->team;
    }

    public function getRegionSeed()
    {
        return $this->regionSeed;
    }

    public function getOverallSeed()
    {
        return $this->overallSeed;
    }
}
