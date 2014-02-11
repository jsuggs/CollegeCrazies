<?php

namespace SofaChamps\Bundle\BracketBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Bracket is the container for a set of BracketGames
 *
 * NOTE: The id and game relationships for the bracket is left up to the concrete implementation
 */
abstract class AbstractBracket implements BracketInterface
{
    protected $games;

    public function __construct()
    {
        $this->games = new ArrayCollection();
    }

    public function addGame(AbstractBracketGame $game)
    {
        if (!$this->games->contains($game)) {
            $this->games->add($game);
        }
    }

    public function removeGame(AbstractBracketGame $game)
    {
        $this->games->removeElement($game);
    }

    public function getGames()
    {
        return $this->games;
    }
}
