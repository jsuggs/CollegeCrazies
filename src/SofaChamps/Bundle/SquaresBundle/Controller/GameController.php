<?php

namespace SofaChamps\Bundle\SquaresBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use SofaChamps\Bundle\SquaresBundle\Entity\Game;
use SofaChamps\Bundle\SquaresBundle\Form\GameFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/game")
 */
class GameController extends BaseController
{
    /**
     * @Route("/new", name="squares_game_new")
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function newAction()
    {
        $form = $this->getGameForm();

        return array(
            'form' => $form->createView(),
        );
    }

    protected function getGameForm(Game $game = null)
    {
        return $this->createForm(new GameFormType(), $game);
    }
}
