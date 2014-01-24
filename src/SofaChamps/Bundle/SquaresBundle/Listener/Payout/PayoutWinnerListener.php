<?php

namespace SofaChamps\Bundle\SquaresBundle\Listener\Payout;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\SquaresBundle\Entity\Payout;
use SofaChamps\Bundle\SquaresBundle\Game\PayoutManager;

/**
 * @DI\DoctrineListener(
 *      events={"preUpdate"}
 * )
 */
class PayoutWinnerListener
{
    private $payoutManager;

    /**
     * @DI\InjectParams({
     *      "payoutManager" = @DI\Inject("sofachamps.squares.payout_manager"),
     * })
     */
    public function __construct(PayoutManager $payoutManager)
    {
        $this->payoutManager = $payoutManager;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Payout) {
            if ($entity->isComplete()) {
                $this->payoutManager->updatePayoutResult($entity);
            }
        }
    }
}
