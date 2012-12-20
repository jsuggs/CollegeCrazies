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
     * @ORM\ManyToMany(targetEntity="League", mappedBy="users", fetch="EXTRA_LAZY")
     */
    protected $leagues;

    /**
     * @ORM\OneToMany(targetEntity="PickSet", mappedBy="user", fetch="EXTRA_LAZY")
     */
    protected $pickSets;

    /**
     * @ORM\ManyToMany(targetEntity="League", mappedBy="commissioners, fetch="EXTRA_LAZY"")
     */
    protected $commissionerLeagues;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $emailVisible = true;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $emailFromCommish = true;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $lastName;

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

    public function removeLeague(League $league)
    {
        if ($this->leagues->contains($league)) {
            $this->leagues->removeElement($league);
            $league->removeUser($this);
        }
    }

    public function setEmailVisible($emailVisible)
    {
        $this->emailVisible = $emailVisible;
    }

    public function getEmailVisible()
    {
        return $this->emailVisible;
    }

    public function setEmailFromCommish($emailFromCommish)
    {
        $this->emailFromCommish = $emailFromCommish;
    }

    public function getEmailFromCommish()
    {
        return $this->emailFromCommish;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function __toString()
    {
        return $this->username;
    }
}
