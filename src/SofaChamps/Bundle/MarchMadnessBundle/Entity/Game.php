<?php

namespace SofaChamps\Bundle\MarchMadnessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\BracketBundle\Entity\AbstractBracketGame;

/**
 * A BracketGame
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="mm_games"
 * )
 */
class Game extends AbstractBracketGame
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\SequenceGenerator(sequenceName="seq_mm_games", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Bracket", inversedBy="games")
     * @ORM\JoinColumn(name="season", referencedColumnName="season")
     */
    protected $bracket;

    /**
     * The name of the bracket game
     * @ORM\Column(type="string", length=32)
     */
    protected $name;

    /**
     * @ORM\OneToOne(targetEntity="Game")
     */
    protected $parent;

    /**
     * @ORM\OneToOne(targetEntity="Game")
     */
    protected $child;

    /**
     * @ORM\OneToMany(targetEntity="BracketPick", mappedBy="game", fetch="EXTRA_LAZY")
     */
    protected $picks;

    /**
     * @ORM\ManyToOne(targetEntity="Region")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="season", referencedColumnName="season"),
     *      @ORM\JoinColumn(name="abbr", referencedColumnName="abbr"),
     * })
     */
    protected $region;

    public function __construct(BracketInterface $bracket, Region $region)
    {
        parent::__construct($bracket);
        $this->region = $region;
    }
}
