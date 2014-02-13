<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Game;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Portfolio;

/**
 * @Route("/{season}/portfolio/{id}")
 */
class PortfolioController extends BaseController
{
    /**
     * @Route("/edit", name="pirc_portfolio_edit")
     * @ParamConverter("portfolio", class="SofaChampsPriceIsRightChallengeBundle:Portfolio", options={"id" = "id"})
     * @Secure(roles="ROLE_USER")
     * @Method({"GET"})
     * @Template
     */
    public function editAction(Portfolio $portfolio, $season)
    {
        $form = $this->getPortfolioForm($portfolio);
        $game = $portfolio->getGame();
        $config = $game->getConfig();
        $bracket = $game->getBracket();

        return array(
            'portfolio' => $portfolio,
            'season' => $season,
            'form' => $form->createView(),
            'bracket' => $bracket,
            'config' => $game->getConfig(),
        );
    }

    /**
     * @Route("/update", name="pirc_portfolio_update")
     * @ParamConverter("bracket", class="SofaChampsMarchMadnessBundle:Bracket", options={"id" = "season"})
     * @Secure(roles="ROLE_USER")
     * @Method({"POST"})
     * @Template("SofaChampsPriceIsRightChallengeBundle:Portfolio:edit.html.twig")
     */
    public function updateAction(Portfolio $portfolio, $season)
    {
        $form = $this->getPortfolioForm($portfolio);
        $user = $this->getUser();
        $game = $portfolio->getGame();
        $config = $game->getConfig();
        $bracket = $game->getBracket();

        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $data = $form->getData();
            $teams = $this->getEntityManager()->getRepository('SofaChampsNCAABundle:Team')->findByIds($data['teams']);

            $this->getEntityManager()->transactional(function ($em) use ($portfolio, $teams) {
                $this->getPortfolioManager()->setPortfolioTeams($portfolio, $teams);
            });

            $this->addMessage('success', 'Portfolio Updated');

            return $this->redirect($this->generateUrl('pirc_portfolio_edit', array(
                'season' => $season,
                'id' => $portfolio->getId(),
            )));
        } else {
            $this->addMessage('warning', 'There was an error updating your portfolio');
        }

        return array(
            'portfolio' => $portfolio,
            'season' => $season,
            'form' => $form->createView(),
            'bracket' => $bracket,
            'config' => $game->getConfig(),
        );
    }
}
