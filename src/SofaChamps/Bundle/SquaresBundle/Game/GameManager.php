<?php

namespace SofaChamps\Bundle\SquaresBundle\Game;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\SquaresBundle\Entity\Game;
use SofaChamps\Bundle\SquaresBundle\Entity\Player;
use SofaChamps\Bundle\SquaresBundle\Entity\Square;

/**
 * GameManager
 *
 * @DI\Service("sofachamps.squares.game_manager")
 */
class GameManager
{
    private $om;

    /**
     * @DI\InjectParams({
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager"),
     * })
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function createGame(User $user)
    {
        // Create all of the squares for the game
        $game = new Game($user);
        $this->om->persist($game);

        foreach (range(0, 9) as $idx) {
            $game->{"row$idx"} = $idx;
            $game->{"col$idx"} = $idx;
        }

        foreach (range(0, 9) as $row) {
            foreach (range(0, 9) as $col) {
                $square = new Square($game, $row, $col);
                $this->om->persist($square);
                $game->addSquare($square);
            }
        }

        // Add the user as a player
        $player = $this->createPlayer($user, $game);

        $this->addPlayerToGame($game, $player);

        return $game;
    }

    public function createPlayer(User $user, Game $game)
    {
        $player = new Player($user, $game);
        $player->setName($user->getUsername());
        $player->setColor($this->generateRandomColor());

        $this->om->persist($player);

        return $player;
    }

    // TODO Move this to a player manager class
    private function generateRandomColor() {
        return sprintf('%06X', mt_rand(0, 0xFFFFFF));
    }

    public function addPlayerToGame(Game $game, Player $player)
    {
        $game->addPlayer($player);
    }

    public function claimSquare(Player $player, Square $square)
    {
        $success = false;

        if (!$square->getPlayer()) {
            $square->setPlayer($player);
            $success = true;
        }

        return $success;
    }
}
