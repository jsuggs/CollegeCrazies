<?php

namespace SofaChamps\Bundle\NFLBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A NFL Team
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="nfl_team"
 * )
 */
class Team
{
    /**
     * Team id/abbreviation
     *
     * @ORM\Id
     * @ORM\Column(type="string", length=3)
     */
    protected $id;

    /**
     * Team name
     *
     * @ORM\Column(type="string")
     * @var string
     */
    protected $name;

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

    public function setId($id)
    {
        $this->id = $id;
    }

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
}
