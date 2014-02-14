<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket;

/**
 * @Route("/")
 */
class MainController extends BaseController
{
    /**
     * @Route("/{season}", name="pirc_season")
     * @ParamConverter("bracket", class="SofaChampsMarchMadnessBundle:Bracket", options={"id" = "season"})
     * @Template
     */
    public function indexAction(Bracket $bracket, $season)
    {
        $user = $this->getUser();

        $games = $user
            ?  $this->getRepository('SofaChampsPriceIsRightChallengeBundle:Game')->findUsersGamesForBracket($user, $bracket)
            : null;

        return array(
            'bracket' => $bracket,
            'season' => $season,
            'games' => $games,
        );
    }

    /**
     * @Route("/", name="pirc_home")
     */
    public function homeAction()
    {
        return $this->redirect($this->generateUrl('pirc_season', array(
            'season' => $this->get('config.curyear'),
        )));
    }
}
