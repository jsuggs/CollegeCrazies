<?php

namespace SofaChamps\Bundle\SquaresBundle\Game;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\SquaresBundle\Entity\Game;
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

    public function createGame()
    {
        // Create all of the squares for the game
        $game = new Game();
        $this->om->persist($game);

        foreach (range(0, 9) as $row) {
            foreach (range(0, 9) as $col) {
                $square = new Square($game, $row, $col);
                $this->om->persist($square);
                $game->addSquare($square);
            }
        }

        return $game;
    }
}
