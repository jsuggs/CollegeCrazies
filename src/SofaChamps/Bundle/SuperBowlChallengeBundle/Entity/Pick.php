<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Entity;

use SofaChamps\Bundle\CoreBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Super Bowl Challenge Pick
 *
 * @ORM\Entity(repositoryClass="PickRepository")
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
     * The user
     *
     * @ORM\ManyToOne(targetEntity="SofaChamps\Bundle\CoreBundle\Entity\User", inversedBy="sbcPicks")
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
     * The users guess for the team to score first in the first quarter
     *
     * @ORM\Column(type="string", length=4)
     * @var string
     */
    protected $firstTeamToScoreFirstQuarter;

    /**
     * The users guess for the team to score first in the second quarter
     *
     * @ORM\Column(type="string", length=4)
     * @var string
     */
    protected $firstTeamToScoreSecondQuarter;

    /**
     * The users guess for the team to score first in the third quarter
     *
     * @ORM\Column(type="string", length=4)
     * @var string
     */
    protected $firstTeamToScoreThirdQuarter;

    /**
     * The users guess for the team to score first in the fourth quarter
     *
     * @ORM\Column(type="string", length=4)
     * @var string
     */
    protected $firstTeamToScoreFourthQuarter;

    /**
     * The users guess for the first bonus question
     *
     * @ORM\Column(type="integer")
     */
    protected $bonusQuestion1;

    /**
     * The users guess for the second bonus question
     *
     * @ORM\Column(type="integer")
     */
    protected $bonusQuestion2;

    /**
     * The users guess for the third bonus question
     *
     * @ORM\Column(type="integer")
     */
    protected $bonusQuestion3;

    /**
     * The users guess for the fourth bonus question
     *
     * @ORM\Column(type="integer")
     */
    protected $bonusQuestion4;

    /**
     * The users guess for the fifth bonus question
     *
     * @ORM\Column(type="integer")
     */
    protected $bonusQuestion5;

    /**
     * The users guess for the sixth bonus question
     *
     * @ORM\Column(type="integer")
     */
    protected $bonusQuestion6;

    /**
     * The users guess for the seventh bonus question
     *
     * @ORM\Column(type="integer")
     */
    protected $bonusQuestion7;

    /**
     * The users guess for the eighth bonus question
     *
     * @ORM\Column(type="integer")
     */
    protected $bonusQuestion8;

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

    /**
     * The points the user gets for guessing the first teams to score
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Range(min=0)
     * @var integer
     */
    protected $firstTeamToScorePoints;

    /**
     * The points the user gets for guessing the bonus questions
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Range(min=0)
     * @var integer
     */
    protected $bonusQuestionPoints;

    public function __construct($year = null)
    {
        if ($year) {
            $this->setYear($year);
        }
    }

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
        $this->year = (int)$year;
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

    public function setFirstTeamToScoreFirstQuarter($firstTeamToScoreFirstQuarter)
    {
        $this->firstTeamToScoreFirstQuarter = $firstTeamToScoreFirstQuarter;
    }

    public function getFirstTeamToScoreFirstQuarter()
    {
        return $this->firstTeamToScoreFirstQuarter;
    }

    public function setFirstTeamToScoreSecondQuarter($firstTeamToScoreSecondQuarter)
    {
        $this->firstTeamToScoreSecondQuarter = $firstTeamToScoreSecondQuarter;
    }

    public function getFirstTeamToScoreSecondQuarter()
    {
        return $this->firstTeamToScoreSecondQuarter;
    }

    public function setFirstTeamToScoreThirdQuarter($firstTeamToScoreThirdQuarter)
    {
        $this->firstTeamToScoreThirdQuarter = $firstTeamToScoreThirdQuarter;
    }

    public function getFirstTeamToScoreThirdQuarter()
    {
        return $this->firstTeamToScoreThirdQuarter;
    }

    public function setFirstTeamToScoreFourthQuarter($firstTeamToScoreFourthQuarter)
    {
        $this->firstTeamToScoreFourthQuarter = $firstTeamToScoreFourthQuarter;
    }

    public function getFirstTeamToScoreFourthQuarter()
    {
        return $this->firstTeamToScoreFourthQuarter;
    }

    public function setBonusQuestion1($bonusQuestion1)
    {
        $this->bonusQuestion1 = $bonusQuestion1;
    }

    public function getBonusQuestion1()
    {
        return $this->bonusQuestion1;
    }

    public function setBonusQuestion2($bonusQuestion2)
    {
        $this->bonusQuestion2 = $bonusQuestion2;
    }

    public function getBonusQuestion2()
    {
        return $this->bonusQuestion2;
    }

    public function setBonusQuestion3($bonusQuestion3)
    {
        $this->bonusQuestion3 = $bonusQuestion3;
    }

    public function getBonusQuestion3()
    {
        return $this->bonusQuestion3;
    }

    public function setBonusQuestion4($bonusQuestion4)
    {
        $this->bonusQuestion4 = $bonusQuestion4;
    }

    public function getBonusQuestion4()
    {
        return $this->bonusQuestion4;
    }

    public function setBonusQuestion5($bonusQuestion5)
    {
        $this->bonusQuestion5 = $bonusQuestion5;
    }

    public function getBonusQuestion5()
    {
        return $this->bonusQuestion5;
    }

    public function setBonusQuestion6($bonusQuestion6)
    {
        $this->bonusQuestion6 = $bonusQuestion6;
    }

    public function getBonusQuestion6()
    {
        return $this->bonusQuestion6;
    }

    public function setBonusQuestion7($bonusQuestion7)
    {
        $this->bonusQuestion7 = $bonusQuestion7;
    }

    public function getBonusQuestion7()
    {
        return $this->bonusQuestion7;
    }

    public function setBonusQuestion8($bonusQuestion8)
    {
        $this->bonusQuestion8 = $bonusQuestion8;
    }

    public function getBonusQuestion8()
    {
        return $this->bonusQuestion8;
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

    public function setFirstTeamToScorePoints($firstTeamToScorePoints)
    {
        $this->firstTeamToScorePoints = $firstTeamToScorePoints;
    }

    public function getFirstTeamToScorePoints()
    {
        return $this->firstTeamToScorePoints;
    }

    public function setBonusQuestionPoints($bonusQuestionPoints)
    {
        $this->bonusQuestionPoints = $bonusQuestionPoints;
    }

    public function getBonusQuestionPoints()
    {
        return $this->bonusQuestionPoints;
    }
}
