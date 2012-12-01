<?php

namespace CollegeCrazies\Bundle\UserBundle\Controller;

use FOS\UserBundle\Controller\ChangePasswordController as BaseController;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ChangePasswordController extends BaseController
{
    /**
     * Change user password
     *
     * @Secure(roles="ROLE_USER")
     * @Template("FOSUserBundle:ChangePassword:changePassword.html.twig")
     */
    public function changePasswordAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $form = $this->container->get('fos_user.change_password.form');
        $formHandler = $this->container->get('fos_user.change_password.form.handler');

        $process = $formHandler->process($user);
        if ($process) {
            $this->setFlash('success', 'Congratulations, your password has been updated.  We encourage you to write it in some concrete so you don\'t forget!');

            return new RedirectResponse($this->container->get('router')->generate('fos_user_profile_show'));
        }

        return array(
            'form' => $form->createView(),
        );
    }
}
