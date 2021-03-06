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
    private $playerManager;
    private $logManager;

    /**
     * @DI\InjectParams({
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager"),
     *      "playerManager" = @DI\Inject("sofachamps.squares.player_manager"),
     *      "logManager" = @DI\Inject("sofachamps.squares.log_manager"),
     * })
     */
    public function __construct(ObjectManager $om, PlayerManager $playerManager, LogManager $logManager)
    {
        $this->om = $om;
        $this->playerManager = $playerManager;
        $this->logManager = $logManager;
    }

    public function createGame(User $user, $shuffleMap = true)
    {
        // Create all of the squares for the game
        $game = new Game($user);
        $this->om->persist($game);

        $this->logManager->createLog($game, 'Game Created');

        $ten = range(0, 9);

        if ($shuffleMap) {
            shuffle($ten);
        }
        foreach ($ten as $idx => $map) {
            $game->{"row$idx"} = $map;
        }

        if ($shuffleMap) {
            shuffle($ten);
        }
        foreach ($ten as $idx => $map) {
            $game->{"col$idx"} = $map;
        }

        foreach (range(0, 9) as $row) {
            foreach (range(0, 9) as $col) {
                $square = new Square($game, $row, $col);
                $this->om->persist($square);
                $game->addSquare($square);
            }
        }

        // Add the user as a player
        $player = $this->playerManager->createPlayer($user, $game, true);

        $this->addPlayerToGame($game, $player);

        return $game;
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
