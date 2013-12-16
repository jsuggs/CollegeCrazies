<?php

namespace SofaChamps\Bundle\CoreBundle\Entity;

interface ConferenceTeamInterface
{
    public function addConferenceMembership(ConferenceMemberInterface $member);
    public function removeConferenceMembership(ConferenceMemberInterface $member);
    public function getConferenceMemberships();
    public function getConference($season);
}
