<?php

namespace SofaChamps\Bundle\BracketBundle\Bracket;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BracketBundle\Entity\AbstractBracket;
use SofaChamps\Bundle\BracketBundle\Entity\AbstractBracketGame;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractBracketManager
{
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

    public function createBracketGame(AbstractBracket $bracket)
    {
        $gameClass = $this->getGameClass();
        $game = new $gameClass($bracket);
        $game->setGameDate(new \DateTime());
        $game->setLocation('TBD');
        $bracket->addGame($game);

        $this->om->persist($game);

        return $game;
    }

    abstract protected function getGameClass();
}
