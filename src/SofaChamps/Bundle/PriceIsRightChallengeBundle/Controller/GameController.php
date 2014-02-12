<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Config;

/**
 * @Route("/{season}/game")
 */
class GameController extends BaseController
{
    /**
     * @Route("/new", name="pirc_game_new")
     * @Secure(roles="ROLE_USER")
     * @Method({"GET"})
     * @Template
     */
    public function newAction(Bracket $bracket, $season)
    {
        $form = $this->getConfigForm(new Config());

        return array(
            'season' => $season,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/create", name="pirc_game_create")
     * @ParamConverter("bracket", class="SofaChampsMarchMadnessBundle:Bracket", options={"id" = "season"})
     * @Secure(roles="ROLE_USER")
     * @Method({"POST"})
     * @Template
     */
    public function createAction(Bracket $bracket, $season)
    {
        $form = $this->getConfigForm(new Config());

        return array(
            'season' => $season,
            'form' => $form->createView(),
        );
    }
}
