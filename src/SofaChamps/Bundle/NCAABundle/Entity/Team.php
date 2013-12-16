<?php

namespace SofaChamps\Bundle\NCAABundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\AbstractTeam;
use SofaChamps\Bundle\CoreBundle\Entity\ConferenceMemberInterface;
use SofaChamps\Bundle\CoreBundle\Entity\ConferenceTeamInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A ncaa team
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="ncaa_teams"
 * )
 */
class Team extends AbstractTeam implements ConferenceTeamInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=5)
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $thumbnail;

    /**
     * Predictions
     *
     * @ORM\OneToMany(targetEntity="SofaChamps\Bundle\BowlPickemBundle\Entity\Prediction", mappedBy="winner", fetch="EXTRA_LAZY")
     */
    protected $predictions;

    /**
     * @ORM\OneToMany(targetEntity="NCAAFConferenceMember", mappedBy="team")
     */
    protected $conferenceMemberships;

    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function addConferenceMembership(ConferenceMemberInterface $member)
    {
        if (!$this->conferenceMemberships->contains($member)) {
            $this->conferenceMemberships->add($member);
        }
    }

    public function removeConferenceMembership(ConferenceMemberInterface $member)
    {
        $this->conferenceMemberships->removeElement($member);
    }

    public function getConference($season)
    {
        return $this->conferenceMemberships->filter(function($membership) use ($season) {
            $season == $membership->getSeason();
        })->first();
    }

    public function getConferenceMemberships()
    {
        return $this->conferenceMemberships;
    }
}
