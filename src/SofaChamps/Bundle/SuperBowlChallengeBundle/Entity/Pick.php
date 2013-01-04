<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Entity;

use CollegeCrazies\Bundle\MainBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Super Bowl Challenge Pick
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="sbc_picks"
 * )
 */
class Pick
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_sbc_pick", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="\CollegeCrazies\Bundle\MainBundle\Entity\User", inversedBy="sbcPicks")
     */
    protected $user;

    /**
     * homeTeamFinalScore
     *
     * @ORM\Column(type="integer")
     * @Assert\Range(min=0)
     * @var integer
     */
    protected $homeTeamFinalScore;

    /**
     * awayTeamFinalScore
     *
     * @ORM\Column(type="integer")
     * @Assert\Range(min=0)
     * @var integer
     */
    protected $awayTeamFinalScore;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setHomeTeamFinalScore($score)
    {
        $this->homeTeamFinalScore = $score;
    }

    public function getHomeTeamFinalScore()
    {
        return $this->homeTeamFinalScore;
    }

    public function setAwayTeamFinalScore($score)
    {
        $this->awayTeamFinalScore = $score;
    }

    public function getAwayTeamFinalScore()
    {
        return $this->awayTeamFinalScore;
    }
}
