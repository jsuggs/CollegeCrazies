<?php

namespace SofaChamps\Bundle\MarchMadnessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\BracketBundle\Entity\AbstractBracket;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The results for a March Madness Bracket
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="mm_brackets"
 * )
 */
class Bracket extends AbstractBracket
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $season;

    /**
     * @ORM\OneToMany(targetEntity="Game", mappedBy="bracket")
     */
    protected $games;

    public function __construct($season)
    {
        parent::__construct();

        $this->season = $season;
    }

    public function getSeason()
    {
        return $this->season;
    }

    public function __toString()
    {
        return (string) $this->season ?: 'New Bracket';
    }
}
