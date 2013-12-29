<?php

namespace SofaChamps\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * The conference must be mapped in concrete class
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractConferenceDivision implements ConferenceDivisionInterface
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

    protected $conference;

    public function __construct(ConferenceInterface $conference)
    {
        $this->conference = $conference;
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

    public function __toString()
    {
        return $this->abbr ?: 'New Conference Division';
    }
}
