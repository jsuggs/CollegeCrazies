<?php

namespace SofaChamps\Bundle\FacebookBundle\User;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\CoreBundle\Entity\User;

/**
 * FacebookUserManager
 *
 * @DI\Service("sofachamps.facebook.user_manager")
 */
class FacebookUserManager
{
    public function updateUserWithFacebookData(User $user, $fbData)
    {
        if (isset($fbData['id'])) {
            $user->setFacebookId($fbData['id']);
            $user->addRole('ROLE_FACEBOOK');
        }

        if (isset($fbData['email'])) {
            $user->setEmail($fbData['email']);
        }
    }
}
