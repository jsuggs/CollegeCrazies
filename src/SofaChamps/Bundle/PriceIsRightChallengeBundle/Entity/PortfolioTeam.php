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
     * @ORM\Column(type="smallint")
     */
    protected $cost;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    public function __construct(Portfolio $portfolio, Team $team)
    {
        $this->portfolio = $portfolio;
        $this->team = $team;
        $this->createdAt = new \DateTime();
    }

    public function getPortfolio()
    {
        return $this->portfolio;
    }

    public function getTeam()
    {
        return $this->team;
    }

    public function getCost()
    {
        return $this->cost;
    }
}
