<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use CollegeCrazies\Bundle\MainBundle\Entity\Team;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Game
 *
 * @ORM\Entity(repositoryClass="GameRepository")
 * @ORM\Table(
 *      name="games"
 * )
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_game", initialValue=1, allocationSize=1)
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
     * The short name of the bowl game
     *
     * @ORM\Column(type="string", length=12)
     * @Assert\MaxLength(12)
     * @var string
     */
    protected $shortName;

    /**
     * @ORM\OneToOne(targetEntity="Team")
     */
    protected $homeTeam;

    /**
     * @ORM\OneToOne(targetEntity="Team")
     */
    protected $awayTeam;

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
     * Gametime baby
     *
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    protected $gameDate;

    /**
     * TV Network
     *
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    protected $network;

    /**
     * homeTeamScore
     *
     * @ORM\Column(type="integer", nullable=true)
     * @var integer
     */
    protected $homeTeamScore;

    /**
     * awayTeamScore
     *
     * @ORM\Column(type="integer", nullable=true)
     * @var integer
     */
    protected $awayTeamScore;

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
     * Bowl location
     *
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    protected $location;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setHomeTeam(Team $team)
    {
        $this->homeTeam = $team;
    }

    public function getHomeTeam()
    {
        return $this->homeTeam;
    }

    public function getAwayTeam()
    {
        return $this->awayTeam;
    }

    public function setAwayTeam(Team $team)
    {
        $this->awayTeam = $team;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    }

    public function getShortName()
    {
        return $this->shortName;
    }

    public function setGameDate($date)
    {
        $this->gameDate = $date;
    }

    public function getGameDate()
    {
        return $this->gameDate;
    }

    public function setNetwork($network)
    {
        $this->network = $network;
    }

    public function getNetwork()
    {
        return $this->network;
    }

    public function setHomeTeamScore($score)
    {
        $this->homeTeamScore = $score;
    }

    public function getHomeTeamScore()
    {
        return $this->homeTeamScore;
    }

    public function setAwayTeamScore($score)
    {
        $this->awayTeamScore = $score;
    }

    public function getAwayTeamScore()
    {
        return $this->awayTeamScore;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getSpread()
    {
        return $this->spread;
    }

    public function setSpread($spread)
    {
        $this->spread = $spread;
    }

    public function getOverunder()
    {
        return $this->overunder;
    }

    public function setOverunder($overunder)
    {
        $this->overunder = $overunder;
    }

    public function isComplete()
    {
        return (isset($this->homeTeamScore) && isset($this->awayTeamScore));
    }

    public function getWinner()
    {
        if (!$this->isComplete()) {
            return null;
        }

        return ($this->homeTeamScore > $this->awayTeamScore) ? $this->homeTeam : $this->awayTeam;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function getLocation()
    {
        return $this->location;
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
        return $this->name;
    }
}
