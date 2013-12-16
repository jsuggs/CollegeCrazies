<?php

namespace SofaChamps\Bundle\NCAABundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\AbstractConference;
use SofaChamps\Bundle\CoreBundle\Entity\ConferenceMemberInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A ncaa conference
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="ncaa_conferences"
 * )
 */
class Conference extends AbstractConference
{
    /**
     * @ORM\OneToMany(targetEntity="NCAAFConferenceMember", mappedBy="conference")
     */
    protected $conferenceMemberships;

    public function __construct()
    {
        $this->conferenceMemberships = new ArrayCollection();
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
