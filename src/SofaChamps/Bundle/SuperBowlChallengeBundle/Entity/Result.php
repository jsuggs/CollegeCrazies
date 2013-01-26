<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Super Bowl Challenge Result
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="sbc_results"
 * )
 */
class Result
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $year;

    /**
     * The final score for the NFC team
     *
     * @ORM\Column(type="integer")
     * @Assert\Range(min=0)
     * @var integer
     */
    protected $nfcFinalScore;

    /**
     * The final score for the AFC team
     *
     * @ORM\Column(type="integer")
     * @Assert\Range(min=0)
     * @var integer
     */
    protected $afcFinalScore;

    /**
     * The score at halftime for the NFC team
     *
     * @ORM\Column(type="integer")
     * @Assert\Range(min=0)
     * @var integer
     */
    protected $nfcHalftimeScore;

    /**
     * The score at halftime for the AFC team
     *
     * @ORM\Column(type="integer")
     * @Assert\Range(min=0)
     * @var integer
     */
    protected $afcHalftimeScore;

    /**
     * The team to score first in the first quarter
     *
     * @ORM\Column(type="string", length=4)
     * @var string
     */
    protected $firstTeamToScoreFirstQuarter;

    /**
     * The team to score first in the second quarter
     *
     * @ORM\Column(type="string", length=4)
     * @var string
     */
    protected $firstTeamToScoreSecondQuarter;

    /**
     * The team to score first in the third quarter
     *
     * @ORM\Column(type="string", length=4)
     * @var string
     */
    protected $firstTeamToScoreThirdQuarter;

    /**
     * The team to score first in the fourth quarter
     *
     * @ORM\Column(type="string", length=4)
     * @var string
     */
    protected $firstTeamToScoreFourthQuarter;

    /**
     * The answer for the first bonus question
     *
     * @ORM\Column(type="integer")
     */
    protected $bonusQuestion1;

    /**
     * The answer for the second bonus question
     *
     * @ORM\Column(type="integer")
     */
    protected $bonusQuestion2;

    /**
     * The answer for the third bonus question
     *
     * @ORM\Column(type="integer")
     */
    protected $bonusQuestion3;

    /**
     * The answer for the fourth bonus question
     *
     * @ORM\Column(type="integer")
     */
    protected $bonusQuestion4;

    /**
     * The answer for the fifth bonus question
     *
     * @ORM\Column(type="integer")
     */
    protected $bonusQuestion5;

    /**
     * The answer for the sixth bonus question
     *
     * @ORM\Column(type="integer")
     */
    protected $bonusQuestion6;

    /**
     * The answer for the seventh bonus question
     *
     * @ORM\Column(type="integer")
     */
    protected $bonusQuestion7;

    /**
     * The answer for the eigth bonus question
     *
     * @ORM\Column(type="integer")
     */
    protected $bonusQuestion8;

    public function __construct($year)
    {
        $this->setYear($year);
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

    public function setFinalScorePoints($finalScorePoints)
    {
        $this->finalScorePoints = $finalScorePoints;
    }

    public function getFinalScorePoints()
    {
        return $this->finalScorePoints;
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
}
