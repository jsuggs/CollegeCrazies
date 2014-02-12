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
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="SofaChamps\Bundle\CoreBundle\Entity\User", inversedBy="pircPortfolios")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket", inversedBy="games")
     * @ORM\JoinColumn(name="season", referencedColumnName="season", nullable=false)
     */
    protected $bracket;

    /**
     * ORM\OneToMany(targetEntity="SofaChampsMarchMadnessBundle:BracketTeam")
     */
    protected $teams;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $score;

    public function __construct(User $user)
    {
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

    public function addTeam(BracketTeam $team)
    {
        $this->teams->add($team);
    }

    public function getTeams()
    {
        return $this->teams;
    }
}
