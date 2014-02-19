<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Game;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Invite\InviteManager;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/{season}/invite")
 */
class InviteController extends BaseController
{
    /**
     * @Route("/{gameId}", name="pirc_invite")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("game", class="SofaChampsPriceIsRightChallengeBundle:Game", options={"id" = "gameId"})
     * @Method({"GET"})
     * @Template
     */
    public function inviteAction(Game $game, $season)
    {
        $form = $this->getInviteForm($game);

        return array(
            'form' => $form->createView(),
            'game' => $game,
            'season' => $season,
        );
    }

    /**
     * @Route("/send/{gameId}", name="pirc_invite_send")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("game", class="SofaChampsPriceIsRightChallengeBundle:Game", options={"id" = "gameId"})
     * @Method({"POST"})
     */
    public function sendInvitesAction(Game $game, $season)
    {
        $form = $this->getInviteForm($game);
        $form->bind($this->getRequest());

        $data = $form->getData();
        $emails = $this->getEmailInputParser()->parseEmails($data['invites']);

        $this->addMessage('info', sprintf('Sending %d invites', count($emails)));

        $user = $this->getUser();
        $this->getInviteManager()->sendInvitesToEmails($game, $user, $emails);

        $this->getEntityManager()->flush();

        return $this->redirect($this->generateUrl('pirc_game_view', array(
            'id' => $game->getId(),
            'season' => $season,
        )));
    }

    /**
     * @Route("/join/{gameId}", name="pirc_join")
     * @ParamConverter("game", class="SofaChampsPriceIsRightChallengeBundle:Game", options={"id" = "gameId"})
     * @Method({"GET"})
     * @Template
     */
    public function joinAction(Game $game, $season)
    {
        $user = $this->getUser();

        if ($user) {
            $portfolio = $game->getUserPortfolio($user);
            if (!$portfolio) {
                $portfolio = $this->getPortfolioManager()->createPortfolio($game, $user);
                $this->getEntityManager()->flush();
                $this->addMessage('success', 'Congratulations, you are in the game!');
            } else {
                $this->addMessage('info', 'You were already in the game');
            }

            return $this->redirect($this->generateUrl('pirc_portfolio_edit', array(
                'season' => $season,
                'id' => $portfolio->getId(),
            )));
        } else {
            $response = $this->getResponse();
            $this->setCookie($response, InviteManager::COOKIE_NAME, $game->getId());
            $response->sendHeaders();

            $this->addMessage('info', 'You can join the squares game after you login or create an account');

            throw new AccessDeniedException('Must be logged in to join Price Is Right Challenge');
        }
    }
}
