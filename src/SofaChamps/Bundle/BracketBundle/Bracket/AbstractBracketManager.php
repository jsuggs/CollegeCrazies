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

    public function createBracketGames(AbstractBracket $bracket, $rounds)
    {
        $bracketGames = array();
        $currentRound = 1;

        // Start with the championship game
        $bracketGames[0][] = $this->createBracketGame($bracket, $currentRound);

        // Create the games
        while ($currentRound <= $rounds) {
            $parentRound = $currentRound - 1;
            $gamesNeedingParents = $bracketGames[$parentRound];
            foreach ($gamesNeedingParents as $parent) {
                foreach ($this->createParentGames($bracket, $parent, $currentRound) as $game) {
                    $bracketGames[$currentRound][] = $game;
                }
            }

            $currentRound++;
        }

        return $bracket;
    }

    public function createParentGames(AbstractBracket $bracket, AbstractBracketGame $game, $round, $numGames = 2)
    {
        $games = array();
        for ($x = 0; $x < $numGames; $x++) {
            $games[] = $this->createBracketGame($bracket, $round, $game);
        }

        return $games;
    }

    public function createBracketGame(AbstractBracket $bracket, $round, AbstractBracketGame $parent = null)
    {
        $gameClass = $this->getGameClass();
        $game = new $gameClass($bracket, $round, $parent);
        $game->setGameDate(new \DateTime());
        $game->setLocation('TBD');
        $bracket->addGame($game);

        $this->om->persist($game);

        return $game;
    }

    abstract protected function getGameClass();
}
