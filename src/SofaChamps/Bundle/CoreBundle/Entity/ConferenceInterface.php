<?php

namespace SofaChamps\Bundle\CoreBundle\Entity;

interface ConferenceInterface
{
    public function setAbbr($abbr);
    public function getAbbr();
    public function setName($name);
    public function getName();
    public function addMember(ConferenceMemberInterface $member);
    public function removeMember(ConferenceMemberInterface $member);
    public function getMembers($season);
}
