<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\Column(type="text", nullable=true)
     */
    protected $motto;

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
     * @ORM\ManyToMany(targetEntity="User", inversedBy="commissionerLeagues")
     * @ORM\JoinTable("league_commissioners")
     */
    protected $commissioners;

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
     * @ORM\ManyToMany(targetEntity="PickSet", inversedBy="leagues")
     * @ORM\JoinTable("pickset_leagues")
     */
    protected $pickSets;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $locked = false;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $note;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->commissioners = new ArrayCollection();
        $this->pickSets = new ArrayCollection();
    }

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

    public function setMotto($motto)
    {
        $this->motto = $motto;
    }

    public function getMotto()
    {
        return $this->motto;
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
        $this->users = $users instanceof ArrayCollection
            ? $users
            : new ArrayCollection($users);
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function setLockTime($time)
    {
        $this->lockTime = $time;
    }

    public function getLockTime()
    {
        return $this->lockTime;
    }

    public function picksLocked()
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

    public function isPublic()
    {
        return (bool) strlen($this->password);
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

    public function addPickSet(PickSet $pickSet)
    {
        $this->pickSets[] = $pickSet;
    }

    public function getPickSetForUser(User $user)
    {
        foreach ($this->pickSets as $pickSet) {
            if ($pickSet->getUser() == $user) {
                return $pickSet;
            }
        }
    }

    public function addCommissioner(User $user)
    {
        if (!$this->users->contains($user)) {
            throw new \Exception('You must be a member of the league to be a commissioner');
        }

        if (!$this->commissioners->contains($user)) {
            $this->commissioners[] = $user;
        }
    }

    public function setCommissioners($commissioners)
    {
        foreach ($commissioners as $commissioner) {
            $this->addCommissioner($commissioner);
        }
    }

    public function getCommissioners()
    {
        return $this->commissioners;
    }

    public function userIsCommissioner(User $user)
    {
        return $this->commissioners->contains($user);
    }

    public function isLocked()
    {
        return (bool) $this->locked;
    }

    public function setLocked($locked)
    {
        $this->locked = (bool) $locked;
    }

    public function getNote()
    {
        return $this->note;
    }

    public function setNote($note)
    {
        $this->note = $note;
    }
}
