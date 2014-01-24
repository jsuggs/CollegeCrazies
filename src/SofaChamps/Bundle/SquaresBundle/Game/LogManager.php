<?php

namespace SofaChamps\Bundle\SquaresBundle\Game;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\SquaresBundle\Entity\Game;
use SofaChamps\Bundle\SquaresBundle\Entity\Log;

/**
 * @DI\Service("sofachamps.squares.log_manager")
 */
class LogManager
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

    public function createLog(Game $game, $message)
    {
        $log = new Log($game, $message);

        $this->om->persist($log);

        $game->addLog($log);

        return $log;
    }
}
