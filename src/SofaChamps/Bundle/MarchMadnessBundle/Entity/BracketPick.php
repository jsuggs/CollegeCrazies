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
 *      name="mm_picks"
 * )
 */
class BracketPick extends AbstractBracketGame
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_mm_games", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="UserBracket", inversedBy="picks")
     */
    protected $bracket;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="picks")
     */
    protected $game;

    /**
     * ORM\ManyToOne(targetEntity="Team")
     * TODO: Add a NCAA mens team bundle
     */
    protected $team;
}
