<?php

namespace CollegeCrazies\Bundle\UserBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RegistrationController extends BaseController
{
    public function registerAction()
    {
        // If the picks are locked, don't allow regisration
        if ($this->container->get('picks_locked_manager')->arePickLocked()) {
            $this->setFlash('warning', 'Regisration is now closed');
            return new RedirectResponse('/');
        }

        $form = $this->container->get('fos_user.registration.form');
        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');

        $process = $formHandler->process($confirmationEnabled);
        if ($process) {
            $user = $form->getData();

            $authUser = false;
            if ($confirmationEnabled) {
                $this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
                $url = $this->container->get('router')->generate('fos_user_registration_check_email');
            } else {
                $authUser = true;
                $url = $this->container->get('router')->generate('pickset_new');
            }

            $this->setFlash('success', 'Account Created!  Welcome to SofaChamps.');
            $response = new RedirectResponse($url);

            if ($authUser) {
                $this->authenticateUser($user, $response);
            }

            return $response;
        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.'.$this->getEngine(), array(
            'form' => $form->createView(),
        ));
    }
}
