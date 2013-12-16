<?php

namespace SofaChamps\Bundle\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

abstract class AbstractConference implements ConferenceInterface
{
    // Note: the conferenceMemberships must be mapped
    protected $conferenceMemberships;

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=5)
     */
    protected $abbr;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    public function __construct()
    {
        $this->conferenceMemberships = new ArrayCollection();
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

    public function addMember(ConferenceMemberInterface $member)
    {
        if (!$this->conferenceMemberships->contains($member)) {
            $this->conferenceMemberships->add($member);
        }
    }

    public function removeMember(ConferenceMemberInterface $member)
    {
        $this->conferenceMemberships->removeElement($member);
    }

    public function getMembers($season)
    {
        return $this->conferenceMemberships->filter(function($membership) use ($season) {
            return $season == $membership->getSeason();
        });
    }
}
