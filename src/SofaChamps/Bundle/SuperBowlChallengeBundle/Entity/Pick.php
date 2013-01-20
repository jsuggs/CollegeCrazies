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
 *      name="sbc_picks",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="user_unique",columns={"user_id", "year"})}
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
     * @ORM\Column(type="integer")
     */
    protected $year;

    /**
     * @ORM\ManyToOne(targetEntity="\CollegeCrazies\Bundle\MainBundle\Entity\User", inversedBy="sbcPicks")
     */
    protected $user;

    /**
     * The users guess for the final score for the NFC team
     *
     * @ORM\Column(type="integer")
     * @Assert\Range(min=0)
     * @var integer
     */
    protected $nfcFinalScore;

    /**
     * The users guess for the final score for the AFC team
     *
     * @ORM\Column(type="integer")
     * @Assert\Range(min=0)
     * @var integer
     */
    protected $afcFinalScore;

    /**
     * The users guess for the score at halftime for the NFC team
     *
     * @ORM\Column(type="integer")
     * @Assert\Range(min=0)
     * @var integer
     */
    protected $nfcHalftimeScore;

    /**
     * The users guess for the score at halftime for the AFC team
     *
     * @ORM\Column(type="integer")
     * @Assert\Range(min=0)
     * @var integer
     */
    protected $afcHalftimeScore;

    /**
     * The total points for this pick
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Range(min=0)
     * @var integer
     */
    protected $totalPoints;

    /**
     * The points the user gets for guessing the final score
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Range(min=0)
     * @var integer
     */
    protected $finalScorePoints;

    /**
     * The points the user gets for guessing the score at halftime
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Range(min=0)
     * @var integer
     */
    protected $halftimeScorePoints;

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

    public function setYear($year)
    {
        $this->year = $year;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function setNfcFinalScore($nfcFinalScore)
    {
        $this->nfcFinalScore = $nfcFinalScore;
    }

    public function getNfcFinalScore()
    {
        return $this->nfcFinalScore;
    }

    public function setAfcFinalScore($afcFinalScore)
    {
        $this->afcFinalScore = $afcFinalScore;
    }

    public function getAfcFinalScore()
    {
        return $this->afcFinalScore;
    }

    public function setNfcHalftimeScore($nfcHalftimeScore)
    {
        $this->nfcHalftimeScore = $nfcHalftimeScore;
    }

    public function getNfcHalftimeScore()
    {
        return $this->nfcHalftimeScore;
    }

    public function setAfcHalftimeScore($afcHalftimeScore)
    {
        $this->afcHalftimeScore = $afcHalftimeScore;
    }

    public function getAfcHalftimeScore()
    {
        return $this->afcHalftimeScore;
    }

    public function setTotalPoints($totalPoints)
    {
        $this->totalPoints = $totalPoints;
    }

    public function getTotalPoints()
    {
        return $this->totalPoints;
    }

    public function setFinalScorePoints($finalScorePoints)
    {
        $this->finalScorePoints = $finalScorePoints;
    }

    public function getFinalScorePoints()
    {
        return $this->finalScorePoints;
    }

    public function setHalftimeScorePoints($halftimeScorePoints)
    {
        $this->halftimeScorePoints = $halftimeScorePoints;
    }

    public function getHalftimeScorePoints()
    {
        return $this->halftimeScorePoints;
    }
}
