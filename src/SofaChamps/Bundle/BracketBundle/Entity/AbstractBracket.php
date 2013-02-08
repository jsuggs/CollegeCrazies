<?php

namespace SofaChamps\Bundle\BracketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Bracket is the container for a set of BracketGames
 *
 * NOTE: The id and game relationships for the bracket is left up to the concrete implementation
 */
abstract class AbstractBracket implements BracketInterface
{
    /**
     * The bracket name
     *
     * @ORM\Column(type="string")
     * @var string
     */
    protected $name;

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
