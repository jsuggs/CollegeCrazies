<?php

namespace SofaChamps\Bundle\SquaresBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Payout for the game
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="squares_game_payouts",
 * )
 */
class Payout
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_squares_payout", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="payouts")
     */
    protected $game;

    /**
     * @ORM\Column
     */
    protected $description;

    /**
     * @ORM\Column
     */
    protected $percentage;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function getGame()
    {
        return $this->game;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;
    }

    public function getPercentage()
    {
        return $this->percentage;
    }
}
