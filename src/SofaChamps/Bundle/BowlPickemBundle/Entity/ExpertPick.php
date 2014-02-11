<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\NCAABundle\Entity\Team;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * An Expert Pick for a game
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="bp_expert_picks"
 * )
 */
class ExpertPick
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_expert_picks", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * The game being picked
     *
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="expertPicks")
     */
    protected $game;

    /**
     * The team picked
     *
     * @ORM\ManyToOne(targetEntity="SofaChamps\Bundle\NCAABundle\Entity\Team")
     */
    protected $team;

    /**
     * Expert that made the pick
     *
     * @ORM\ManyToOne(targetEntity="Expert", inversedBy="expertPicks")
     */
    protected $expert;

    /**
     * Any additional details/description around the pick
     *
     * @ORM\Column(type="text")
     * @Assert\NotNull
     */
    protected $description;

    /**
     * When then invite occurred
     *
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    protected $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setGame(Game $game)
    {
        $this->game = $game;
    }

    public function getGame()
    {
        return $this->game;
    }

    public function setTeam(Team $team)
    {
        // TODO - Add check to make sure a valid team for this game
        $this->team = $team;
    }

    public function getTeam()
    {
        return $this->team;
    }

    public function setExpert(Expert $expert)
    {
        $this->expert = $expert;
    }

    public function getExpert()
    {
        return $this->expert;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function __toString()
    {
        $this->id ?: 'New Expert Pick';
    }
}
