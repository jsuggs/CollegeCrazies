<?php

namespace SofaChamps\Bundle\SquaresBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Game
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="squares_games",
 * )
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_square_game", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * The Bowl Game Name
     *
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column
     */
    protected $homeTeam;

    /**
     * @ORM\Column
     */
    protected $awayTeam;

    /**
     * @ORM\OneToMany(targetEntity="Square", mappedBy="Game")
     */
    protected $squares;

    /**
     * @ORM\OneToMany(targetEntity="Payout", mappedBy="Game")
     */
    protected $payouts;

    public function __construct()
    {
        $this->squares = new ArrayCollection();
        $this->payouts = new ArrayCollection();
    }

    public function setSquares($squares)
    {
        $this->squares = $squares;
    }

    public function addSquare(Square $square)
    {
        if (!$this->squares->contains($square)) {
            $this->squares->add($square);
        }
    }

    public function getSquares()
    {
        return $this->squares;
    }
}
