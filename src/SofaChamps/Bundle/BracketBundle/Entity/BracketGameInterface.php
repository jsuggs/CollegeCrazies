<?php

namespace SofaChamps\Bundle\BracketBundle\Entity;

interface BracketGameInterface
{
    public function getBracket();
    public function setParent(BracketGameInterface $parent);
    public function getParent();
    public function addChild(BracketGameInterface $child);
    public function getChildren();
    public function getRound();
}
