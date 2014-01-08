<?php

namespace SofaChamps\Bundle\SquaresBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
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
     * @ORM\Column(type="integer")
     */
    protected $row;
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $col;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="squares")
     */
    protected $game;

    public function __construct(Game $game, $row, $col)
    {
        $this->game = $game;
        $this->row = $row;
        $this->col = $col;
    }
}
