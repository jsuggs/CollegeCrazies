<?php

namespace SofaChamps\Bundle\SquaresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\Invite as BaseInvite;
use SofaChamps\Bundle\CoreBundle\Entity\User;

/**
 * @ORM\Entity
 */
class Invite extends BaseInvite
{
    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="invites")
     * @ORM\JoinColumn(name="squares_game_id", referencedColumnName="id")
     */
    protected $game;

    public function __construct(User $user, Game $game, $email)
    {
        parent::__construct($user, $email);
        $this->game = $game;
    }

    public function getGame()
    {
        return $this->game;
    }
}
