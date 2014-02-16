<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Price is Right Challenge Game
 *
 * @ORM\Entity(repositoryClass="GameRepository")
 * @ORM\Table(
 *      name="pirc_games"
 * )
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_pirc_games", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket", inversedBy="pircGames")
     * @ORM\JoinColumn(name="season", referencedColumnName="season", nullable=false)
     */
    protected $bracket;

    /**
     * @ORM\OneToOne(targetEntity="Config", mappedBy="game")
     */
    protected $config;

    /**
     * @ORM\OneToMany(targetEntity="Portfolio", mappedBy="game")
     */
    protected $portfolios;

    /**
     * @ORM\OneToMany(targetEntity="GameManager", mappedBy="game")
     */
    protected $managers;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $password;

    public function __construct(Bracket $bracket, Config $config)
    {
        $this->bracket = $bracket;
        $this->config = $config;
        $config->setGame($this);
        $this->portfolios = new ArrayCollection();
        $this->managers = new ArrayCollection();
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

    public function getBracket()
    {
        return $this->bracket;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function addPortfolio(Portfolio $portfolio)
    {
        if (!$this->portfolios->contains($portfolio)) {
            $this->portfolios->add($portfolio);
        }
    }

    public function getPortfolios()
    {
        return $this->portfolios;
    }

    public function getUserPortfolio(User $user)
    {
        return $this->portfolios->filter(function($portfolio) use ($user) {
            return $portfolio->getUser() == $user;
        })->first();
    }

    public function addManager(GameManager $manager)
    {
        if (!$this->managers->contains($manager)) {
            $this->managers->add($manager);
        }
    }

    public function setPassword($password)
    {
        $this->password = trim($password);
    }

    public function isManager(User $user)
    {
        return $this->managers->exists(function($idx, GameManager $manager) use ($user) {
            return $manager->getUser() == $user;
        });
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function isPublic()
    {
        return (bool) !($this->password && strlen($this->password) > 0);
    }

    public function setPublic($public)
    {
        //noop
    }
}
