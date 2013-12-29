<?php

namespace SofaChamps\Bundle\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * The divisions must be mapped in concrete class
 */
abstract class AbstractConference implements ConferenceInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=5)
     */
    protected $abbr;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    protected $divisions;

    public function __construct()
    {
        $this->divisions = new ArrayCollection();
    }

    public function setAbbr($abbr)
    {
        $this->abbr = $abbr;
    }

    public function getAbbr()
    {
        return $this->abbr;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function addConferenceDivision(ConferenceDivisionInterface $division)
    {
        if (!$this->divisions->contains($division)) {
            $this->divisions->add($division);
        }
    }

    public function removeConferenceDivision(ConferenceDivisionInterface $division)
    {
        $this->divisions->removeElement($division);
    }

    public function getConferenceDivisions()
    {
        return $this->divisions;
    }

    public function __toString()
    {
        return $this->abbr ?: 'New Conference';
    }
}
