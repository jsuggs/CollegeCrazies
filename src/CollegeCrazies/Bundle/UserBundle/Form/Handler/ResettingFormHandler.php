<?php

namespace CollegeCrazies\Bundle\UserBundle\Form\Handler;

use FOS\UserBundle\Form\Handler\ResettingFormHandler as BaseHandler;
use FOS\UserBundle\Model\UserInterface;

class ResettingFormHandler extends BaseHandler
{
    protected function onSuccess(UserInterface $user)
    {
        $user->setPlainPassword($this->getNewPassword());
        $user->setConfirmationToken(null);
        $user->setPasswordRequestedAt(null);
        $user->setEnabled(true);
        $this->userManager->updateUser($user);
    }
}
