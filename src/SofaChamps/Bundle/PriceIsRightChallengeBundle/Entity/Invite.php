<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity;

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
     * @ORM\JoinColumn(name="pirc_game_id", referencedColumnName="id")
     */
    protected $pircGame;

    public function __construct(User $user, Game $game, $email)
    {
        parent::__construct($user, $email);
        $this->pircGame = $game;
    }

    public function getPircGame()
    {
        return $this->pircGame;
    }
}
