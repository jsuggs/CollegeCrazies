<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use CollegeCrazies\Bundle\MainBundle\Entity\User;
use CollegeCrazies\Bundle\MainBundle\Entity\League;
use CollegeCrazies\Bundle\MainBundle\Entity\Pick;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Users Pick for a single game
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="picksets"
 * )
 */
class PickSet {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="pickset_seq", initialValue=1, allocationSize=100)
     */
    protected $id;
    /**
     * @ORM\OneToOne(targetEntity="User")
     */
    protected $user;

    /**
     * @ORM\OneToOne(targetEntity="League")
     */
    protected $league;

    /**
     * @ORM\ManyToMany(targetEntity="Pick")
     */
    protected $picks;

    public function setLeague(League $league)
    {
        $this->league = $league;
    }

    public function getLeague()
    {
        return $this->league;
    }

    public function setPicks($picks)
    {
        $this->picks = $picks;
    }

    public function addPick(Pick $pick)
    {
        $this->picks[] = $pick;
    }

    public function getPicks()
    {
        return $this->picks;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }
}
