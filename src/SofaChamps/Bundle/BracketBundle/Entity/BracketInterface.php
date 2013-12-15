<?php

namespace SofaChamps\Bundle\BracketBundle\Entity;

/**
 * A Bracket is the container for a set of BracketGames
 */
interface BracketInterface
{
    public function addGame(BracketGame $game);
    public function removeGame(BracketGame $game);
    public function getGames();
}
