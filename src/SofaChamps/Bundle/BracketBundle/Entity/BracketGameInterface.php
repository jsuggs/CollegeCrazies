<?php

namespace SofaChamps\Bundle\BracketBundle\Entity;

interface BracketGameInterface
{
    public function setBracket(BracketInterface $bracket);
    public function getBracket();
    public function setParent(BracketGameInterface $parent);
    public function getParent();
    public function setChild(BracketGameInterface $child);
    public function getChild();
    public function getRound();
}
