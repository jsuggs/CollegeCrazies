<?php

namespace SofaChamps\Bundle\SquaresBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Square
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="squares_squares",
 * )
 */
class Square
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="squares")
     */
    protected $game;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $row;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $col;

    /**
     * @ORM\ManyToOne(targetEntity="Player", inversedBy="squaresSquares")
     */
    protected $owner;

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

    public function setOwner(Player $owner)
    {
        $this->owner = $owner;
    }

    public function getOwner()
    {
        return $this->owner;
    }
}
