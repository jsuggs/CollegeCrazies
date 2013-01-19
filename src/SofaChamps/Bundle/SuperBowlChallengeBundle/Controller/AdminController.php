<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin")
 */
class AdminController extends BaseController
{
    /**
     * @Route("/{year}", name="sbc_admin_config")
     * @Secure(roles="ROLE_ADMIN")
     * @Template
     */
    public function configAction($year)
    {
        $config = $this->getConfig($year);
        $form = $this->getConfigForm($config);
        $request = $this->getRequest();

        if ($request->getMethod() === 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->flush();
                $this->get('session')->getFlashBag()->set('success', 'Config updated');
            }
        }

        return array(
            'config' => $config,
            'year' => $year,
            'form' => $form->createView(),
        );
    }
}
