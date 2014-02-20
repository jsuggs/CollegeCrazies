<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serialize;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket;
use SofaChamps\Bundle\NCAABundle\Entity\Team;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A user portfolio of teams
 *
 * @ORM\Entity(repositoryClass="PortfolioRepository")
 * @ORM\Table(
 *      name="pirc_portfolios"
 * )
 * @Serialize\ExclusionPolicy("all")
 */
class Portfolio
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_pirc_portfolios", initialValue=1, allocationSize=1)
     * @Serialize\Expose
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Serialize\Expose
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="portfolios")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id", nullable=false)
     */
    protected $game;

    /**
     * @ORM\ManyToOne(targetEntity="SofaChamps\Bundle\CoreBundle\Entity\User", inversedBy="pircPortfolios")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @Serialize\Expose
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="PortfolioTeam", mappedBy="portfolio", cascade={"all"}, orphanRemoval=true)
     * @Serialize\Expose
     */
    protected $teams;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Serialize\Expose
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

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getGame()
    {
        return $this->game;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function addTeam(PortfolioTeam $team)
    {
        $this->teams->add($team);
    }

    public function setTeams(Collection $teams)
    {
        $this->teams = $teams;
    }

    public function getTeams()
    {
        return $this->teams;
    }

    public function hasTeam(Team $team)
    {
        return $this->teams->exists(function($idx, PortfolioTeam $portfolioTeam) use($team) {
            return $portfolioTeam->getTeam()->getId() == $team->getId();
        });
    }

    public function setScore($score)
    {
        $this->score = $score;
    }

    public function getScore()
    {
        return $this->score;
    }

    /**
     * Assert\Range(max=100, message"The team cost cannot be greater than 100")
     */
    public function getTeamCost()
    {
        return array_sum($this->teams->map(function($team) {
            return $team->getCost();
        })->toArray());
    }
}
