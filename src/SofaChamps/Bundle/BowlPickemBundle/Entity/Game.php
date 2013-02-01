<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Team;
use SofaChamps\Bundle\CoreBundle\Entity\AbstractGame;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Bowl Game
 *
 * @ORM\Entity(repositoryClass="GameRepository")
 * @ORM\Table(
 *      name="games",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="uniq_games_season_tiebreaker_priority", columns={"season", "tiebreakerPriority"}),
 *          @ORM\UniqueConstraint(name="uniq_games_hometeam_id", columns={"season", "homeTeam_id"}),
 *          @ORM\UniqueConstraint(name="uniq_games_awayteam_id", columns={"season", "awayTeam_id"}),
 *      }
 * )
 */
class Game extends AbstractGame
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_game", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * The bowl season for this game
     *
     * @ORM\Column(type="integer", length=4)
     * @Assert\Range(min=2012, max=2020)
     * @var integer
     */
    protected $season;

    /**
     * @ORM\OneToOne(targetEntity="Team")
     */
    protected $homeTeam;

    /**
     * @ORM\OneToOne(targetEntity="Team")
     */
    protected $awayTeam;

    /**
     * The short name of the bowl game
     *
     * @ORM\Column(type="string", length=12)
     * @Assert\Length(max=12)
     * @var string
     */
    protected $shortName;

    /**
     * @ORM\OneToMany(targetEntity="Pick", mappedBy="game", fetch="EXTRA_LAZY")
     */
    protected $picks;

    /**
     * The spread of the game
     *
     * @ORM\Column(type="float")
     * @var float
     */
    protected $spread;

    /**
     * The over/under
     *
     * @ORM\Column(type="float")
     * @var int
     */
    protected $overunder;

    /**
     * TV Network
     *
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    protected $network;

    /**
     * description
     *
     * @ORM\Column(type="text", nullable=true)
     * @var string
     */
    protected $description;

    /**
     * Predictions
     *
     * @ORM\OneToMany(targetEntity="Prediction", mappedBy="game", fetch="EXTRA_LAZY")
     * @var Prediction
     */
    protected $predictions;

    /**
     * The tiebreaker priority
     *
     * @ORM\Column(type="smallint", nullable=true)
     * @var int
     */
    protected $tiebreakerPriority;

    /**
     * Expert Picks for the game
     *
     * @ORM\OneToMany(targetEntity="ExpertPick", mappedBy="game", fetch="EXTRA_LAZY")
     */
    protected $expertPicks;

    public function __construct()
    {
        $this->expertPicks = new ArrayCollection();
    }

    public function __clone()
    {
        $this->id = null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setSeason($season)
    {
        $this->season = $season;
    }

    public function getSeason()
    {
        return $this->season;
    }

    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    }

    public function getShortName()
    {
        return $this->shortName;
    }

    public function setNetwork($network)
    {
        $this->network = $network;
    }

    public function getNetwork()
    {
        return $this->network;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setSpread($spread)
    {
        $this->spread = $spread;
    }

    public function getSpread()
    {
        return $this->spread;
    }

    public function setOverunder($overunder)
    {
        $this->overunder = $overunder;
    }

    public function getOverunder()
    {
        return $this->overunder;
    }

    public function setTiebreakerPriority($tiebreakerPriority)
    {
        $this->tiebreakerPriority = $tiebreakerPriority;
    }

    public function getTiebreakerPriority()
    {
        return $this->tiebreakerPriority;
    }

    public function addExpertPick(ExpertPick $expertPick)
    {
        $this->expertPicks->add($expertPick);
    }

    public function getExpertPicks()
    {
        return $this->expertPicks;
    }

    /**
     * getFavorite
     *
     * A negative spread indicates that the designated home team is the favorite
     */
    public function getFavorite()
    {
        return $this->spread <= 0
            ? $this->homeTeam
            : $this->awayTeam;
    }

    public function getUnderdog()
    {
        return $this->spread > 0
            ? $this->homeTeam
            : $this->awayTeam;
    }

    public function __toString()
    {
        return sprintf('%d - %s', $this->season, $this->name);
    }
}
