<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A user portfolio of teams
 *
 * @ORM\Entity(repositoryClass="PortfolioRepository")
 * @ORM\Table(
 *      name="pirc_portfolios"
 * )
 */
class Portfolio
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_pirc_portfolios", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="portfolios")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id", nullable=false)
     */
    protected $game;

    /**
     * @ORM\ManyToOne(targetEntity="SofaChamps\Bundle\CoreBundle\Entity\User", inversedBy="pircPortfolios")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="PortfolioTeam", mappedBy="portfolio")
     */
    protected $teams;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $score;

    public function __construct(Game $game, User $user)
    {
        $this->game = $game;
        $this->user = $user;
        $this->teams = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function addTeam(PortfolioTeam $team)
    {
        $this->teams->add($team);
    }

    public function setTeams(ArrayCollection $teams)
    {
        $this->teams = $teams;
    }

    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @Assert\Range(max=100, message"The team cost cannot be greater than 100")
     */
    public function getTeamCost()
    {
        return array_sum($this->teams->map(function($team) {
            return $team->getCost();
        })->toArray());
    }
}
