<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\NCAABundle\Entity\Team;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * An entity to hold who is a manager for a PIRC Game
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="pirc_portfolio_teams"
 * )
 */
class PortfolioTeam
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Portfolio", inversedBy="teams")
     */
    protected $portfolio;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SofaChamps\Bundle\NCAABundle\Entity\Team")
     */
    protected $team;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $round1Score;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $round2Score;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $round3Score;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $round4Score;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $round5Score;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $round6Score;

    public function __construct(Portfolio $portfolio, Team $team)
    {
        $this->portfolio = $portfolio;
        $this->team = $team;
    }

    public function getPortfolio()
    {
        return $this->portfolio;
    }

    public function getTeam()
    {
        return $this->team;
    }

    public function getTeamName()
    {
        return $this->team->getId();
    }

    public function setRoundScore($round, $score)
    {
        $this->{sprintf('round%dScore', $round)} = $score;
    }

    public function getRoundScore($round)
    {
        return $this->{sprintf('round%dScore', $round)};
    }

    public function getTotalScore()
    {
        return array_sum(array_map(function ($round) {
            return $this->getRoundScore($round);
        }, range(1, 6)));
    }
}
