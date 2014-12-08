<?php

namespace SofaChamps\Bundle\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomepageController extends CoreController
{
    /**
     * @Route("/", name="core_home")
     */
    public function homepageAction()
    {
        // This is temporary
        return $this->redirect($this->generateUrl('bp_home', array(
            'season' => 2014,
        )));
        $user = $this->getUser();

        $template = $this->get('security.context')->isGranted('ROLE_USER')
            ? 'SofaChampsCoreBundle::homepage.auth.html.twig'
            : 'SofaChampsCoreBundle::homepage.unauth.html.twig';

        return $this->render($template , array(
            'user' => $user,
            'year' => $this->get('config.curyear'),
        ));
    }

    private function getCurrentBowlPickemSeason()
    {
        return $this->get('sofachamps.bp.season_manager')->getCurrentSeason();
    }
}
