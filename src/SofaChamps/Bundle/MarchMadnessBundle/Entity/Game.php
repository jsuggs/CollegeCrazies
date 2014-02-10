<?php

namespace SofaChamps\Bundle\MarchMadnessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\BracketBundle\Entity\AbstractBracketGame;

/**
 * A BracketGame
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="mm_games"
 * )
 */
class Game extends AbstractBracketGame
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=16)
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Bracket", inversedBy="games")
     * @ORM\JoinColumn(name="season", referencedColumnName="season", nullable=false)
     */
    protected $bracket;

    /**
     * @ORM\ManyToOne(targetEntity="Region", inversedBy="games")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="season", referencedColumnName="season"),
     *      @ORM\JoinColumn(name="region", referencedColumnName="abbr"),
     * })
     */
    protected $region;

    /**
     * @ORM\ManyToOne(targetEntity="BracketTeam")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="season", referencedColumnName="season"),
     *      @ORM\JoinColumn(name="hometeam_id", referencedColumnName="team_id")
     * })
     */
    protected $homeTeam;

    /**
     * @ORM\ManyToOne(targetEntity="BracketTeam")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="season", referencedColumnName="season"),
     *      @ORM\JoinColumn(name="awayteam_id", referencedColumnName="team_id")
     * })
     */
    protected $awayTeam;

    /**
     * The name of the bracket game
     * @ORM\Column(type="string", length=32)
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="BracketPick", mappedBy="game", fetch="EXTRA_LAZY")
     */
    protected $picks;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $round;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $index;

    public function __construct(BracketInterface $bracket, Region $region)
    {
        parent::__construct($bracket);
        $this->region = $region;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getHomeTeam()
    {
        return $this->homeTeam;
    }

    public function getAwayTeam()
    {
        return $this->awayTeam;
    }

    public function getRound()
    {
        return $this->round;
    }

    public function getIndex()
    {
        return $this->index;
    }
}
