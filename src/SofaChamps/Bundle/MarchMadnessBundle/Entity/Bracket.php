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
    protected $year;

    /**
     * @ORM\OneToMany(targetEntity="Game", mappedBy="bracket")
     */
    protected $games;

    public function setYear($year)
    {
        $this->year = (int)$year;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function __toString()
    {
        return $this->year ?: 'New Bracket';
    }
}
