<?php

namespace SofaChamps\Bundle\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

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

    public function __toString()
    {
        return $this->abbr ?: 'New Conference';
    }
}
