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
}
