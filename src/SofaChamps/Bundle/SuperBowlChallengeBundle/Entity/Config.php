<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Entity;

use CollegeCrazies\Bundle\MainBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
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

    public function setCloseTime(\DateTime $closeTime)
    {
        $this->closeTime = $closeTime;
    }

    public function getCloseTime()
    {
        return $this->closeTime;
    }
}
