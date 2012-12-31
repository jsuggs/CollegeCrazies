<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

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
