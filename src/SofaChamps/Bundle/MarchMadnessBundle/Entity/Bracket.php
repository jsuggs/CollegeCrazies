<?php

namespace SofaChamps\Bundle\MarchMadnessBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\BracketBundle\Entity\AbstractBracket;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The results for a March Madness Bracket
 *
 * @ORM\Entity(repositoryClass="BracketRepository")
 * @ORM\Table(
 *      name="mm_brackets"
 * )
 */
class Bracket extends AbstractBracket
{
    /**
     * @ORM\Id
     * @ORM\Column(type="smallint")
     */
    protected $season;

    /**
     * @ORM\OneToMany(targetEntity="Game", mappedBy="bracket")
     */
    protected $games;

    /**
     * @ORM\OneToMany(targetEntity="Region", mappedBy="bracket")
     * @ORM\OrderBy({"index" = "ASC"})
     */
    protected $regions;

    /**
     * @ORM\OneToMany(targetEntity="BracketTeam", mappedBy="bracket")
     */
    protected $teams;

    public function __construct($season)
    {
        parent::__construct();

        $this->season = $season;
        $this->games = new ArrayCollection();
        $this->regions = new ArrayCollection();
        $this->teams = new ArrayCollection();
    }

    public function getSeason()
    {
        return $this->season;
    }

    public function getRegions()
    {
        return $this->regions;
    }

    public function getGames()
    {
        return $this->games;
    }

    public function getTeams()
    {
        return $this->teams;
    }

    public function getGamesForRound($round)
    {
        return $this->games->filter(function($game) use ($round) {
            return $game->getRound() == $round;
        });
    }

    public function __toString()
    {
        return (string) $this->season ?: 'New Bracket';
    }
}
