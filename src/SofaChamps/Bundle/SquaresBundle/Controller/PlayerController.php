<?php

namespace SofaChamps\Bundle\SquaresBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\SquaresBundle\Entity\Player;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/player")
 */
class PlayerController extends BaseController
{
    /**
     * @Route("/update/{playerId}", name="squares_player_update")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("player", class="SofaChampsSquaresBundle:Player", options={"id" = "playerId"})
     * @Method({"POST"})
     */
    public function updateAction(Player $player)
    {
        $form = $this->getPlayerForm($player);
        $form->bind($this->getRequest());
        if ($form->isValid()) {
            $success = true;
            $this->getEntityManager()->flush();
        } else {
            $success = false;
        }

        return new JsonResponse(array(
            'success' => $success,
        ));
    }
}
