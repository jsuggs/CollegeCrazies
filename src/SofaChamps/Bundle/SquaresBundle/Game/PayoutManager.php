<?php

namespace SofaChamps\Bundle\SquaresBundle\Game;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\SquaresBundle\Entity\Game;
use SofaChamps\Bundle\SquaresBundle\Entity\Payout;
use SofaChamps\Bundle\SquaresBundle\Entity\Player;
use SofaChamps\Bundle\SquaresBundle\Entity\Square;

/**
 * @DI\Service("sofachamps.squares.payout_manager")
 */
class PayoutManager
{
    private $om;
    private $logManager;

    /**
     * @DI\InjectParams({
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager"),
     *      "logManager" = @DI\Inject("sofachamps.squares.log_manager"),
     * })
     */
    public function __construct(ObjectManager $om, LogManager $logManager)
    {
        $this->om = $om;
        $this->logManager = $logManager;
    }

    public function updatePayoutResult(Payout $payout)
    {
        $game = $payout->getGame();

        $winnerRow = $game->getTranslatedRow($payout->getRowResult());
        $winnerCol = $game->getTranslatedCol($payout->getColResult());

        $square = $game->getSquare($winnerRow, $winnerCol);

        if ($player = $square->getPlayer()) {
            $this->setWinnerForPayout($payout, $player);
        } elseif ($payout->isCarryover()) {
            $this->carryoverPayout($payout);
        } elseif ($game->isForceWinner()) {
            // TODO
            // $this->getForcedWinner($payout);
        }
    }

    protected function carryoverPayout(Payout $payout)
    {
        $game = $payout->getGame();

        $nextPayout = $game->getNextPayout($payout);
        if ($nextPayout) {
            $this->logManager->createLog($game, sprintf(
                'There was no winner for Payout "%s", carrying over to the next Payout "%s"',
                $payout->getDescription(),
                $nextPayout->getDescription()
            ));

            $nextPayout->incrementPercentage($payout->getPercentage());
            $payout->setPercentage(0);
        }
    }

    public function setWinnerForPayout(Payout $payout, Player $player)
    {
        $payout->setWinner($player);

        $game = $payout->getGame();
        $this->logManager->createLog($game, sprintf(
            'Player "%s" won Payout "%s"',
            $player->getName(),
            $payout->getDescription()
        ));
    }

    protected function getForcedWinner(Payout $payout)
    {
        // Since we must find a winner for the game, lets run through some different algorithms until one works
        $game = $payout->getGame();

        // 1) Translate the rows/cols
        $winnerRow = $game->getTranslatedRow($payout->getColResult());
        $winnerCol = $game->getTranslatedCol($payout->getRowResult());
        if ($square = $game->getSquare($winnerRow, $winnerCol)) {
            //
        }
    }
}
