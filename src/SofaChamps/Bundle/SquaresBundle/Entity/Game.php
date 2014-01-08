<?php

namespace SofaChamps\Bundle\SquaresBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\User;
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
     * @ORM\SequenceGenerator(sequenceName="seq_squares_game", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="SofaChamps\Bundle\CoreBundle\Entity\User", inversedBy="squaresGames")
     */
    protected $user;

    /**
     * @ORM\Column(type="string", length=255)
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
     * @ORM\Column(type="integer")
     * @Assert\Range(min=0, minMessage="Cost Per Square must be greater than 0")
     */
    protected $costPerSquare;

    /**
     * @ORM\OneToMany(targetEntity="Square", mappedBy="game")
     */
    protected $squares;

    /**
     * @ORM\OneToMany(targetEntity="Payout", mappedBy="game")
     */
    protected $payouts;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->squares = new ArrayCollection();
        $this->payouts = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setHomeTeam($homeTeam)
    {
        $this->homeTeam = $homeTeam;
    }

    public function getHomeTeam()
    {
        return $this->homeTeam;
    }

    public function setAwayTeam($awayTeam)
    {
        $this->awayTeam = $awayTeam;
    }

    public function getAwayTeam()
    {
        return $this->awayTeam;
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

    public function getSquare($row, $col)
    {
        return $this->squares->filter(function ($square) use ($row, $col) {
            return $square->getRow() == $row && $square->getCol() == $col;
        })->current();
    }

    public function getUser()
    {
        return $this->user;
    }

    public function addPayout(Payout $payout)
    {
        if (!$this->payouts->contains($payout)) {
            $this->payouts->add($payout);
        }
    }

    public function removePayout(Payout $payout)
    {
        if ($this->payouts->contains($payout)) {
            $this->payouts->removeElement($payout);
        }
    }

    public function getPayouts()
    {
        return $this->payouts;
    }

    /**
     * @Assert\Range(max=100)
     */
    public function getPayoutPercentages()
    {
        return array_sum($this->payouts->map(function($payout) {
            return $payout->getPercentage();
        })->toArray());
    }

    public function setCostPerSquare($costPerSquare)
    {
        $this->costPerSquare = $costPerSquare;
    }

    public function getCostPerSquare()
    {
        return $this->costPerSquare;
    }
}
