<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * An entity to hold who is a manager for a PIRC Game
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="pirc_game_managers"
 * )
 */
class GameManager
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SofaChamps\Bundle\CoreBundle\Entity\User", inversedBy="pircManagers")
     */
    protected $user;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="managers")
     */
    protected $game;

    public function __construct(Game $game, User $user)
    {
        $this->game = $game;
        $this->user = $user;
        $this->createdAt = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setPassword($password)
    {
        $this->password = trim($password);
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function isPublic()
    {
        return (bool) !($this->password && strlen($this->password) > 0);
    }

    public function setPublic($public)
    {
        //noop
    }
}
