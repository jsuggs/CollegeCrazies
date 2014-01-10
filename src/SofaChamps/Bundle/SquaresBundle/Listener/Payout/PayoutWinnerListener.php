<?php

namespace SofaChamps\Bundle\SquaresBundle\Listener\Payout;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use JMS\DiExtraBundle\Annotation\DoctrineListener;
use SofaChamps\Bundle\SquaresBundle\Entity\Payout;

/**
 * @DoctrineListener(
 *      events={"prePersist"}
 * )
 */
class PayoutWinnerListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Payout) {
            if ($entity->isComplete()) {
                $winner = $entity
                    ->getGame()
                    ->getSquare($entity->getRowResult(), $payout->getColResult())
                    ->getOwner();

                $payout->setWinner($winner);
            }
        }
    }
}
