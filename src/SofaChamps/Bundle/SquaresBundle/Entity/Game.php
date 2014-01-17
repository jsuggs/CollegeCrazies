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
 *      name="squares_games"
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

    /**
     * @ORM\OneToMany(targetEntity="Player", mappedBy="game")
     */
    protected $players;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $locked = false;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $row0;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $row1;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $row2;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $row3;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $row4;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $row5;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $row6;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $row7;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $row8;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $row9;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $col0;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $col1;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $col2;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $col3;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $col4;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $col5;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $col6;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $col7;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $col8;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $col9;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->squares = new ArrayCollection();
        $this->payouts = new ArrayCollection();
        $this->players = new ArrayCollection();
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

    public function getClaimedSquares()
    {
        return $this->squares->filter(function ($square) {
            return $square->getPlayer();
        });
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

    public function getCostPerSquare($dollars = false)
    {
        return $dollars
            ? ($this->costPerSquare / 100)
            : $this->costPerSquare;
    }

    public function getTranslatedRow($row)
    {
        return $this->{"row$row"};
    }

    public function getTranslatedCol($col)
    {
        return $this->{"col$col"};
    }

    public function addPlayer(Player $player)
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
        }
    }

    public function getPlayers()
    {
        return $this->players;
    }

    public function getPlayerForUser(User $user)
    {
        return $this->players->filter(function($player) use ($user) {
            return $player->getUser() == $user;
        })->first();
    }

    public function isPlayer(User $user)
    {
        return $this->players->exists(function ($player) use ($user) {
            return $player->getUser() == $user;
        });
    }

    public function setLocked($locked = null)
    {
        $this->locked = (bool) $locked;
    }

    public function isLocked()
    {
        return $this->locked;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }
}
