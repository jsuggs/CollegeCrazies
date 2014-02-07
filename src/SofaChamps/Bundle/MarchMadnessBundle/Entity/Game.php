<?php

namespace SofaChamps\Bundle\MarchMadnessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\BracketBundle\Entity\AbstractBracketGame;
use SofaChamps\Bundle\CoreBundle\Entity\AbstractTeam;

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
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_mm_games", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Bracket", inversedBy="games")
     * @ORM\JoinColumn(name="season", referencedColumnName="season")
     */
    protected $bracket;

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
}
