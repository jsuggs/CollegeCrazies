<?php

namespace SofaChamps\Bundle\MarchMadnessBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket;

/**
 * @Route("/admin")
 */
class AdminController extends BaseController
{
    /**
     * @Route("/", name="mm_admin")
     * @Template
     */
    public function indexAction()
    {
        $games = $this->getGames();

        return array(
            'games' => $games,
        );
    }

    /**
     * @Route("/{year}/home", name="mm_admin_year")
     * @ParamConverter("bracket", class="SofaChampsMarchMadnessBundle:Bracket", options={"id" = "year"})
     * @Secure(roles="ROLE_ADMIN")
     * @Template
     */
    public function homeAction(Bracket $bracket)
    {
        $request = $this->getRequest();

        return array(
            'bracket' => $bracket,
        );
    }
}
