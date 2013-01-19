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
    protected $nfcHalfScore;

    /**
     * The users guess for the score at halftime for the AFC team
     *
     * @ORM\Column(type="integer")
     * @Assert\Range(min=0)
     * @var integer
     */
    protected $afcHalfScore;

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

    public function setNfcHalfScore($nfcHalfScore)
    {
        $this->nfcHalfScore = $nfcHalfScore;
    }

    public function getNfcHalfScore()
    {
        return $this->nfcHalfScore;
    }

    public function setAfcHalfScore($afcHalfScore)
    {
        $this->afcHalfScore = $afcHalfScore;
    }

    public function getAfcHalfScore()
    {
        return $this->afcHalfScore;
    }

    public function setFinalScorePoints($finalScorePoints)
    {
        $this->finalScorePoints = $finalScorePoints;
    }

    public function getFinalScorePoints()
    {
        return $this->finalScorePoints;
    }
}
