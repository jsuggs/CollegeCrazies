<?php

namespace SofaChamps\Bundle\SquaresBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serialize;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Square
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="squares_squares"
 * )
 * @Serialize\ExclusionPolicy("all")
 */
class Square
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="squares")
     * @Serialize\Expose
     */
    protected $game;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @Serialize\Expose
     */
    protected $row;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @Serialize\Expose
     */
    protected $col;

    /**
     * @ORM\ManyToOne(targetEntity="Player", inversedBy="squares")
     * @Serialize\Expose
     */
    protected $player;

    public function __construct(Game $game, $row, $col)
    {
        $this->game = $game;
        $this->row = $row;
        $this->col = $col;
    }

    public function getGame()
    {
        return $this->game;
    }

    public function getRow()
    {
        return $this->row;
    }

    public function getCol()
    {
        return $this->col;
    }

    public function setPlayer(Player $player)
    {
        $this->player = $player;
    }

    public function getPlayer()
    {
        return $this->player;
    }
}
