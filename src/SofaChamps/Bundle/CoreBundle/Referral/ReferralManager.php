<?php

namespace SofaChamps\Bundle\CoreBundle\Referral;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\CoreBundle\Entity\User;

/**
 * @DI\Service("sofachamps.referrer.referral_manager")
 */
class ReferralManager
{
    public function addReferrerToUser(User $user, User $referrer)
    {
        // If the user already has a referrer, then don't overwrite
        if (!$user->getReferrer()) {
            $user->setReferrer($referrer);
        }
    }
}
