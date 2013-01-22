<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Entity;

use CollegeCrazies\Bundle\MainBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\NFLBundle\Entity\Team;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Super Bowl Challenge Config
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="sbc_config"
 * )
 */
class Config
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $year;

    /**
     * When the game starts
     * @ORM\Column(type="datetime")
     */
    protected $startTime;

    /**
     * When picks close
     * @ORM\Column(type="datetime")
     */
    protected $closeTime;

    /**
     * The NFC Team playing in the superbowl
     *
     * @ORM\ManyToOne(targetEntity="\SofaChamps\Bundle\NFLBundle\Entity\Team")
     */
    protected $nfcTeam;

    /**
     * The AFC Team playing in the superbowl
     *
     * @ORM\ManyToOne(targetEntity="\SofaChamps\Bundle\NFLBundle\Entity\Team")
     */
    protected $afcTeam;

    /**
     * The maximum points the user can get for guessing the final score
     *
     * @ORM\Column(type="integer")
     * @Assert\Range(min=0)
     * @var integer
     */
    protected $finalScorePoints;

    /**
     * The maximum points the user can get for guessing the score at halftime
     *
     * @ORM\Column(type="integer")
     * @Assert\Range(min=0)
     * @var integer
     */
    protected $halftimeScorePoints;

    /**
     * The points the user gets for correctly guessing a team to score first in a quarter
     *
     * @ORM\Column(type="integer")
     * @Assert\Range(min=0)
     * @var integer
     */
    protected $firstTeamToScoreInAQuarterPoints;

    /**
     * The points the user gets for correctly guessing neither team to score in a quarter
     *
     * @ORM\Column(type="integer")
     * @Assert\Range(min=0)
     * @var integer
     */
    protected $neitherTeamToScoreInAQuarterPoints;

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

    public function setStartTime(\DateTime $startTime)
    {
        $this->startTime = $startTime;
    }

    public function getStartTime()
    {
        return $this->startTime;
    }

    public function setNfcTeam(Team $team)
    {
        $this->nfcTeam = $team;
    }

    public function getNfcTeam()
    {
        return $this->nfcTeam;
    }

    public function setAfcTeam(Team $team)
    {
        $this->afcTeam = $team;
    }

    public function getAfcTeam()
    {
        return $this->afcTeam;
    }

    public function setCloseTime(\DateTime $closeTime)
    {
        $this->closeTime = $closeTime;
    }

    public function getCloseTime()
    {
        return $this->closeTime;
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

    public function setFirstTeamToScoreInAQuarterPoints($firstTeamToScoreInAQuarterPoints)
    {
        $this->firstTeamToScoreInAQuarterPoints = $firstTeamToScoreInAQuarterPoints;
    }

    public function getFirstTeamToScoreInAQuarterPoints()
    {
        return $this->firstTeamToScoreInAQuarterPoints;
    }

    public function setNeitherTeamToScoreInAQuarterPoints($neitherTeamToScoreInAQuarterPoints)
    {
        $this->neitherTeamToScoreInAQuarterPoints = $neitherTeamToScoreInAQuarterPoints;
    }

    public function getNeitherTeamToScoreInAQuarterPoints()
    {
        return $this->neitherTeamToScoreInAQuarterPoints;
    }
}
