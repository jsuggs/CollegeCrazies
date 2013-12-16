<?php

namespace SofaChamps\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractConferenceMember implements ConferenceMemberInterface
{
    // Note: You must map the conference and team in the concrete class

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $season;

    public function setConference(ConferenceInterface $conference)
    {
        $this->conference = $conference;
    }

    public function getConference()
    {
        return $this->conference;
    }

    public function setTeam(TeamInterface $team)
    {
        $this->team = $team;
    }

    public function getTeam()
    {
        return $this->team;
    }

    public function setSeason($season)
    {
        $this->season = $season;
    }

    public function getSeason()
    {
        return $this->season;
    }
}
