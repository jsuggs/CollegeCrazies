<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\NCAABundle\Entity\Team;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Users Pick for a single game
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="picks"
 * )
 */
class Pick
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_pick", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="PickSet", inversedBy="picks")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    protected $pickSet;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="picks")
     */
    protected $game;

    /**
     * @ORM\ManyToOne(targetEntity="SofaChamps\Bundle\NCAABundle\Entity\Team")
     */
    protected $team;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Length(min=0)
     */
    protected $confidence;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        if ($id) {
            $this->id = $id;
        }
    }

    public function getTeam()
    {
        return $this->team;
    }

    public function setTeam(Team $team)
    {
        $this->team = $team;
    }

    /**
     * @Assert\True()
     */
    public function isTeamValid()
    {
        return $this->team == $this->game->getHomeTeam() || $this->team == $this->game->getAwayTeam();
    }

    public function getGame()
    {
        return $this->game;
    }

    public function setGame(Game $game)
    {
        $this->game = $game;
    }

    public function getConfidence()
    {
        return $this->confidence;
    }

    public function setConfidence($confidence)
    {
        $this->confidence = $confidence;
    }

    public function getPickSet()
    {
        return $this->pickSet;
    }

    public function setPickSet($pickSet)
    {
        $this->pickSet = $pickSet;
    }
}
