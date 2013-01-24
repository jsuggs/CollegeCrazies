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
     * @Route("/config/{year}", name="sbc_admin_config")
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
                $config = $form->getData();

                $em = $this->get('doctrine.orm.entity_manager');
                $em->persist($config);
                $em->flush($config);

                $this->get('session')->getFlashBag()->set('success', 'Config updated');
            }
        }

        return array(
            'config' => $config,
            'year' => $year,
            'form' => $form->createView(),
            'user' => $this->getUser(),
        );
    }

    /**
     * @Route("/result/{year}", name="sbc_admin_result")
     * @Secure(roles="ROLE_ADMIN")
     * @Template
     */
    public function resultAction($year)
    {
        $result = $this->getResult($year);
        $form = $this->getResultForm($result);
        $request = $this->getRequest();

        if ($request->getMethod() === 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $result = $form->getData();

                $em = $this->get('doctrine.orm.entity_manager');
                $em->persist($result);
                $em->flush($result);

                $this->get('session')->getFlashBag()->set('success', 'Result updated');
            }
        }

        return array(
            'result' => $result,
            'year' => $year,
            'form' => $form->createView(),
            'user' => $this->getUser(),
        );
    }
}
