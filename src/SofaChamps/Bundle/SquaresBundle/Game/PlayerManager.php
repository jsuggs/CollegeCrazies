<?php

namespace SofaChamps\Bundle\SquaresBundle\Game;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\SquaresBundle\Entity\Game;
use SofaChamps\Bundle\SquaresBundle\Entity\Player;

/**
 * @DI\Service("sofachamps.squares.player_manager")
 */
class PlayerManager
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

    public function createPlayer(User $user, Game $game, $isAdmin = false)
    {
        $player = new Player($user, $game);
        $player->setName(substr($user->getUsername(), 0, 10));
        $player->setColor($this->generateRandomColor());
        $player->setAdmin($isAdmin);

        $this->om->persist($player);

        return $player;
    }

    private function generateRandomColor() {
        return sprintf('%06X', mt_rand(0, 0xFFFFFF));
    }
}
