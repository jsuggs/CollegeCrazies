<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Game;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Config;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Game;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\GameManager as Manager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * GameManager
 *
 * @DI\Service("sofachamps.pirc.game_manager")
 */
class GameManager
{
    private $om;
    private $dispatcher;

    /**
     * @DI\InjectParams({
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager"),
     *      "dispatcher" = @DI\Inject("event_dispatcher"),
     * })
     */
    public function __construct(ObjectManager $om, EventDispatcherInterface $dispatcher)
    {
        $this->om = $om;
        $this->dispatcher = $dispatcher;
    }

    public function createGame(Bracket $bracket, User $user)
    {
        $config = new Config();
        $this->om->persist($config);

        $game = new Game($bracket, $config);
        $this->om->persist($game);

        $gameManager = $this->createGameManager($game, $user);
        $game->addManager($gameManager);

        return $game;
    }

    public function createGameManager(Game $game, User $user)
    {
        $manager = new Manager($game, $user);
        $this->om->persist($manager);

        return $manager;
    }
}

