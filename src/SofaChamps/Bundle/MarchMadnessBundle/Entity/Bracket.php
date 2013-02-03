<?php

namespace SofaChamps\Bundle\MarchMadnessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\BracketBundle\Entity\AbstractBracket;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * March Madness Bracket
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
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_mm_brackets", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Game", mappedBy="bracket")
     */
    protected $games;

    /**
     * @ORM\Column(type="integer")
     */
    protected $year;

    public function setYear($year)
    {
        $this->year = (int)$year;
    }

    public function getYear()
    {
        return $this->year;
    }
}
