<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A User
 *
 * @ORM\Entity(repositoryClass="UserRepository")
 * @ORM\Table(
 *      name="users"
 * )
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_user", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="League", mappedBy="users")
     */
    protected $leagues;

    /**
     * @ORM\OneToMany(targetEntity="PickSet", mappedBy="user")
     */
    protected $pickSets;

    /**
     * @ORM\ManyToMany(targetEntity="League", mappedBy="commissioners")
     */
    protected $commissionerLeagues;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getPickSets()
    {
        return $this->pickSets;
    }

    public function addPickSet(PickSet $pickSet)
    {
        $this->pickSets[] = $pickSet;
    }

    public function setPickSets($pickSets)
    {
        $this->pickSets = $pickSets;
    }

    public function isInTheLeague(League $league)
    {
        return $this->leagues->contains($league);
    }

    public function getLeagues()
    {
        return $this->leagues;
    }

    public function addLeague(League $league)
    {
        $this->leagues[] = $league;
        $league->addUser($this);
    }

    public function getPicksetForLeague(League $league)
    {
        foreach ($this->pickSets as $pickSet) {
            if ($pickSet->getLeague() == $league) {
                return $pickSet;
            }
        }
    }

    public function __toString()
    {
        return $this->username;
    }
}
