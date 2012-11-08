<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A League
 *
 * @ORM\Entity(repositoryClass="LeagueRepository")
 * @ORM\Table(
 *      name="leagues"
 * )
 */
class League
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_league", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $password;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="leagues")
     * @ORM\JoinTable("user_league")
     */
    protected $users;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lockTime;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $public;

    /**
     * @ORM\OneToOne(targetEntity="LeagueMetadata")
     */
    protected $metadata;

    /**
     * @ORM\OneToMany(targetEntity="PickSet", mappedBy="league")
     */
    protected $pickSets;

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function addUser(User $user)
    {
        $this->users[] = $user;
    }

    public function setUsers($users)
    {
        $this->users = $users;
    }

    public function setLockTime($time)
    {
        $this->lockTime = $time;
    }

    public function getLockTime()
    {
        return $this->lockTime;
    }

    public function isLocked()
    {
        $now = new \DateTime();
        return ($this->lockTime < $now);
    }

    public function getPublic()
    {
        return $this->public;
    }

    public function setPublic($public)
    {
        $this->public = $public;
    }

    public function getMetadata()
    {
        return $this->metadata;
    }

    public function setMetadata(LeagueMetadata $metadata)
    {
        $this->metadata = $metadata;
    }

    public function userCanView(User $user)
    {
        return $this->public || $this->isUserInLeague($user);
    }

    public function isUserInLeague(User $user)
    {
        return $this->users->contains($user);
    }

    public function getPickSets()
    {
        return $this->pickSets;
    }
}
