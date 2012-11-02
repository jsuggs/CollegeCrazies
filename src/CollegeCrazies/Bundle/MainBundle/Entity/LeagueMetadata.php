<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * The metadata for a league
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="league_metadata"
 * )
 */
class LeagueMetadata
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_league_metadata", initialValue=1, allocationSize=1)
     */
    protected $id;

    public function __construct(League $league)
    {
        $this->id = $league->getId();
    }

    public function getId()
    {
        return $this->id;
    }
}
