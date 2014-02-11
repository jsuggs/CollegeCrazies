<?php

namespace SofaChamps\Bundle\NFLBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\AbstractTeam;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A NFL Team
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="nfl_team"
 * )
 */
class Team extends AbstractTeam
{
    /**
     * Team id/abbreviation
     *
     * @ORM\Id
     * @ORM\Column(type="string", length=3)
     */
    protected $id;

    /**
     * Conference
     *
     * @ORM\Column(type="string", length=3)
     * @var string
     */
    protected $conference;

    /**
     * Division
     *
     * @ORM\Column(type="string", length=5)
     * @var string
     */
    protected $division;

    public function setConference($conference)
    {
        $this->conference = $conference;
    }

    public function getConference()
    {
        return $this->conference;
    }

    public function setDivision($division)
    {
        $this->division = $division;
    }

    public function getDivision()
    {
        return $this->division;
    }

    public function __toString()
    {
        return $this->id;
    }
}
