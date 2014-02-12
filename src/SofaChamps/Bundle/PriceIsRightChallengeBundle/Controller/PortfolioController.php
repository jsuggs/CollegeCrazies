<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket;

/**
 * @Route("/{season}/portfolio")
 */
class PortfolioController extends BaseController
{
    /**
     * @Route("/edit", name="pirc_edit")
     * @ParamConverter("bracket", class="SofaChampsMarchMadnessBundle:Bracket", options={"id" = "season"})
     * @Secure(roles="ROLE_USER")
     * @Method({"GET"})
     * @Template
     */
    public function editAction(Bracket $bracket, $season)
    {
        $user = $this->getUser();
        $portfolio = $this->getUserPortfolio($user, $season);
        $form = $this->getPortfolioForm($bracket, $portfolio);

        return array(
            'portfolio' => $portfolio,
            'season' => $season,
            'form' => $form->createView(),
            'user' => $user,
        );
    }

    /**
     * @Route("/update", name="pirc_update")
     * @ParamConverter("bracket", class="SofaChampsMarchMadnessBundle:Bracket", options={"id" = "season"})
     * @Secure(roles="ROLE_USER")
     * @Method({"POST"})
     */
    public function updateAction(Bracket $bracket, $season)
    {
        $user = $this->getUser();
        $portfolio = $this->getUserPortfolio($user, $season);
        $form = $this->getPortfolioForm($bracket, $portfolio);

        return array(
            'portfolio' => $portfolio,
            'season' => $season,
            'form' => $form->createView(),
            'user' => $user,
        );
    }
}
