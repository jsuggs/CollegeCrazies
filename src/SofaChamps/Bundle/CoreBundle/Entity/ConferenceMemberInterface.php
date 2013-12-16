<?php

namespace SofaChamps\Bundle\CoreBundle\Entity;

interface ConferenceMemberInterface
{
    public function setConference(ConferenceInterface $conference);
    public function getConference();
    public function setTeam(TeamInterface $team);
    public function getTeam();
    public function setSeason($season);
    public function getSeason();
}
