<?php

namespace SofaChamps\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The config for a game module
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="core_module_config"
 * )
 */
class ModuleConfig
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $year;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Module", inversedBy="configs")
     */
    protected $module;

    /**
     * When the game starts
     *
     * @ORM\Column(type="datetime")
     */
    protected $startTime;

    /**
     * When game ends
     *
     * @ORM\Column(type="datetime")
     */
    protected $endTime;

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

    public function setEndTime(\DateTime $endTime)
    {
        $this->endTime = $endTime;
    }

    public function getEndTime()
    {
        return $this->endTime;
    }
}
