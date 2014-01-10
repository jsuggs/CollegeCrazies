<?php

namespace SofaChamps\Bundle\SquaresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Player in a squares
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="squares_players",
 * )
 */
class Player
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_squares_players", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="SofaChamps\Bundle\CoreBundle\Entity\User", inversedBy="squaresPlayers")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="players")
     */
    protected $game;

    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=6)
     * @Assert\Regex(pattern="/^[0-f]{6}$/", message="Must be a valid hex color")
     */
    protected $color;

    /**
     * @ORM\OneToMany(targetEntity="Payout", mappedBy="winner")
     */
    protected $winners;

    public function __construct(User $user, Game $game)
    {
        $this->user = $user;
        $this->game = $game;
    }

    public function getGame()
    {
        return $this->game;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setColor($color)
    {
        $this->color = $color;
    }

    public function getColor()
    {
        return $this->color;
    }
}
