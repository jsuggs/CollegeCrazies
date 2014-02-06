<?php

namespace SofaChamps\Bundle\SquaresBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serialize;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Player in a squares
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="squares_players"
 * )
 * @Serialize\ExclusionPolicy("all")
 */
class Player
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_squares_players", initialValue=1, allocationSize=1)
     * @Serialize\Expose
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
     * @Serialize\Expose
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=6)
     * @Assert\Regex(pattern="/^[0-f]{6}$/", message="Must be a valid hex color")
     * @Serialize\Expose
     */
    protected $color;

    /**
     * @ORM\OneToMany(targetEntity="Square", mappedBy="player")
     */
    protected $squares;

    /**
     * @ORM\OneToMany(targetEntity="Payout", mappedBy="winner")
     */
    protected $winners;

    /**
     * @ORM\Column(type="boolean", options={"default"=false})
     * @Serialize\Expose
     */
    protected $admin = false;

    public function __construct(User $user, Game $game)
    {
        $this->user = $user;
        $this->game = $game;
        $this->squares = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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

    public function setAdmin($admin)
    {
        $this->admin = (bool) $admin;
    }

    public function isAdmin()
    {
        return $this->admin;
    }

    public function getSquares()
    {
        return $this->squares;
    }
}
