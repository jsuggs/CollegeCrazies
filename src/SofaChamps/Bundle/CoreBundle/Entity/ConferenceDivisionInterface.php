<?php

namespace SofaChamps\Bundle\CoreBundle\Entity;

interface ConferenceDivisionInterface
{
    public function setAbbr($abbr);
    public function getAbbr();
    public function setName($name);
    public function getName();
}
