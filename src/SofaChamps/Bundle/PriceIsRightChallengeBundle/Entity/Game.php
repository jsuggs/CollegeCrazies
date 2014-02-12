<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Price is Right Challenge Game
 *
 * @ORM\Entity
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
     * @ORM\ManyToMany(targetEntity="Portfolio")
     * @ORM\JoinTable("pirc_game_portfolios")
     */
    protected $portfolios;

    /**
     * @ORM\OneToMany(targetEntity="GameManager", mappedBy="game")
     */
    protected $managers;

    /**
     * @ORM\OneToOne(targetEntity="Config", mappedBy="game")
     */
    protected $config;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $password;

    public function __construct()
    {
        $this->portfolios = new ArrayCollection();
        $this->managers = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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

    public function setPassword($password)
    {
        $this->password = trim($password);
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
