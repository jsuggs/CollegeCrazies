<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Game;

/**
 * @Route("/{season}/portfolio/{game_id}")
 */
class PortfolioController extends BaseController
{
    /**
     * @Route("/edit", name="pirc_portfolio_edit")
     * @ParamConverter("bracket", class="SofaChampsMarchMadnessBundle:Bracket", options={"id" = "season"})
     * @ParamConverter("game", class="SofaChampsMarchMadnessBundle:Game", options={"id" = "game_id"})
     * @Secure(roles="ROLE_USER")
     * @Method({"GET"})
     * @Template
     */
    public function editAction(Bracket $bracket, Game $game, $season)
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
     * @Route("/update", name="pirc_portfolio_update")
     * @ParamConverter("bracket", class="SofaChampsMarchMadnessBundle:Bracket", options={"id" = "season"})
     * @Secure(roles="ROLE_USER")
     * @Method({"POST"})
     */
    public function updateAction(Bracket $bracket, $season)
    {
        $user = $this->getUser();
        $portfolio = $this->getUserPortfolio($user, $season);
        $form = $this->getPortfolioForm($bracket, $portfolio);

        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $data = $form->getData();
            $teamIds = array();
            foreach ($data as $idx => $ids) {
                $teamIds = array_merge($ids, $teamIds);
            }
            $teams = $this->getRepository('SofaChampsNCAABundle:Team')->findById($teamIds);
            $this->getPortfolioManager()->setPortfolioTeams($portfolio, $teams);

            $this->getEntityManager()->flush();

            $this->addMessage('success', 'Portfolio Updated');

            return $this->redirect($this->generateUrl('pirc_portfolio_edit', array(
                'season' => $season,
            )));
        } else {
            $this->addMessage('warning', 'There was an error updating your portfolio');
        }

        return array(
            'portfolio' => $portfolio,
            'season' => $season,
            'form' => $form->createView(),
            'user' => $user,
        );
    }
}
