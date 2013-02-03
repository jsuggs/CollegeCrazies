<?php

namespace SofaChamps\Bundle\BracketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Bracket is the container for a set of BracketGames
 */
abstract class AbstractBracket implements BracketInterface
{
    protected $id;
    protected $name;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getname()
    {
        return $this->name;
    }

    public function addGame(BracketGame $game)
    {
        $this->games[] = $game;
    }

    public function removeGame(BracketGame $game)
    {
        $this->games->removeElement($game);
    }

    public function getGames()
    {
        return $this->games;
    }
}
