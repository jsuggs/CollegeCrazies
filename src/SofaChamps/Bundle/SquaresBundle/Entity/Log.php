<?php

namespace SofaChamps\Bundle\SquaresBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * An event to be logged for the game
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="squares_log"
 * )
 */
class Log
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_squares_payout", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="logs")
     */
    protected $game;

    /**
     * @ORM\Column
     */
    protected $message;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    public function __construct(Game $game, $message)
    {
        $this->game = $game;
        $this->message = $message;
        $this->createdAt = new \DateTime();
    }

    public function getGame()
    {
        return $this->game;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
