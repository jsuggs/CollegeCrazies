<?php

namespace SofaChamps\Bundle\CoreBundle\Entity;

use JMS\DiExtraBundle\Annotation as DI;
use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * MyUserManager
 *
 * @DI\Service("sofachamps.user.manager", public=false, parent="fos_user.user_manager.default")
 */
class MyUserManager extends UserManager
{
    public function loadUserByUsername($username)
    {
        $user = $this->findUserByUsernameOrEmail($username);

        if (!$user) {
            throw new UsernameNotFoundException(sprintf('No user with name "%s" was found.', $username));
        }

        return $user;
    }
}
