<?php

namespace SofaChamps\Bundle\BracketBundle\Bracket;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @DI\Service("sofachamps.bracket.bracket_manager")
 */
class BracketManager
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

    public function createBracket($rounds)
    {
        $bracket = $this->getNewBracket();

        // Start with the championship game
        $game = $this->getNewBracketGame();

        return $bracket;
    }

    public function getNewBracket()
    {
        return new Bracket();
    }

    public functoin getNewBracketGame()
    {
        return new BracketGame();
    }
}
