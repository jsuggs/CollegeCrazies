<?php

namespace SofaChamps\Bundle\SquaresBundle\Game;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\SquaresBundle\Entity\Game;
use SofaChamps\Bundle\SquaresBundle\Entity\Square;

/**
 * @DI\Service("sofachamps.squares.payout_manager")
 */
class PayoutManager
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

    public function setWinnerForPayout(Payout $payout)
    {
        $game = $payout->getGame();

        $winnerRow = $game->getTranslatedRow($payout->getRowResult());
        $winnerCol = $game->getTranslatedCol($payout->getColResult());

        $square = $game->getSquare($winnerRow, $winnerCol);

        $square->setUser($user);
    }
}
