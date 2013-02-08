<?php

namespace SofaChamps\Bundle\MarchMadnessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\BracketBundle\Entity\AbstractBracket;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A users bracket picks for a March Madness Bracket
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="mm_user_brackets"
 * )
 * TODO: Should this extend AbstractBracket?
 */
class UserBracket extends AbstractBracket
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_mm_user_brackets", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="SofaChamps\Bundle\CoreBundle\Entity\User", inversedBy="pickSets")
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="BracketPick", mappedBy="bracket", cascade={"persist"}, fetch="EXTRA_LAZY")
     */
    protected $picks;

    public function setYear($year)
    {
        $this->year = (int)$year;
    }

    public function getYear()
    {
        return $this->year;
    }
}
