<?php

namespace SofaChamps\Bundle\SquaresBundle\Listener\Payout;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use JMS\DiExtraBundle\Annotation\DoctrineListener;
use SofaChamps\Bundle\SquaresBundle\Entity\Payout;

/**
 * @DoctrineListener(
 *      events={"preUpdate"}
 * )
 */
class PayoutWinnerListener
{
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Payout) {
            if ($entity->isComplete()) {
                $winner = $entity
                    ->getGame()
                    ->getSquare($entity->getRowResult(), $entity->getColResult())
                    ->getOwner();

                $entity->setWinner($winner);
            }
        }
    }
}
