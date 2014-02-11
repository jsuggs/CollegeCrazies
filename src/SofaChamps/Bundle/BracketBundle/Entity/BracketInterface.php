<?php

namespace SofaChamps\Bundle\BracketBundle\Entity;

/**
 * A Bracket is the container for a set of BracketGames
 */
interface BracketInterface
{
    public function addGame(AbstractBracketGame $game);
    public function removeGame(AbstractBracketGame $game);
    public function getGames();
}
