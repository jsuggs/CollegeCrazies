<?php

namespace SofaChamps\Bundle\CoreBundle\Entity;

interface TeamInterface
{
    public function setId($id);
    public function getId();
    public function setName($name);
    public function getName();
}
