<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serialize;
use SofaChamps\Bundle\CoreBundle\Entity\Image;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Vlabs\MediaBundle\Annotation\Vlabs;

/**
 * A League
 *
 * @ORM\Entity(repositoryClass="LeagueRepository")
 * @ORM\Table(
 *      name="leagues",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="uniq_league_logo_id", columns={"logo_id"})}
 * )
 * @Serialize\ExclusionPolicy("all")
 */
class League
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_league", initialValue=1, allocationSize=1)
     * @Serialize\Expose
     */
    protected $id;

    /**
     * The bowl season for this league
     *
     * @ORM\ManyToOne(targetEntity="Season")
     * @ORM\JoinColumn(name="season", referencedColumnName="season")
     * @Serialize\Expose
     */
    protected $season;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serialize\Expose
     */
    protected $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Serialize\Expose
     */
    protected $motto;

    /**
     * @ORM\OneToOne(targetEntity="SofaChamps\Bundle\CoreBundle\Entity\Image", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Vlabs\Media(identifier="image_entity", upload_dir="uploads/bp/logo")
     * @Assert\Valid
     */
    protected $logo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $password;

    /**
     * @ORM\ManyToMany(targetEntity="SofaChamps\Bundle\CoreBundle\Entity\User", inversedBy="leagues", fetch="EXTRA_LAZY")
     * @ORM\JoinTable("user_league")
     */
    protected $users;

    /**
     * @ORM\ManyToMany(targetEntity="SofaChamps\Bundle\CoreBundle\Entity\User", inversedBy="commissionerLeagues", fetch="EXTRA_LAZY")
     * @ORM\JoinTable("league_commissioners")
     */
    protected $commissioners;

    /**
     * @ORM\OneToOne(targetEntity="LeagueMetadata")
     */
    protected $metadata;

    /**
     * @ORM\ManyToMany(targetEntity="PickSet", inversedBy="leagues", fetch="EXTRA_LAZY")
     * @ORM\JoinTable("pickset_leagues")
     */
    protected $pickSets;

    /**
     * @ORM\Column(type="boolean")
     * @Serialize\Expose
     */
    protected $locked = false;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Serialize\Expose
     */
    protected $note;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->commissioners = new ArrayCollection();
        $this->pickSets = new ArrayCollection();
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSeason(Season $season)
    {
        $this->season = $season;
    }

    public function getSeason()
    {
        return $this->season;
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

    public function setLogo(Image $logo)
    {
        $this->logo = $logo;
    }

    public function getLogo()
    {
        return $this->logo;
    }

    public function setPassword($password)
    {
        $this->password = trim($password);
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function addUser(User $user)
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }
    }

    public function setUsers($users)
    {
        $this->users = $users instanceof ArrayCollection
            ? $users
            : new ArrayCollection($users);
    }

    public function removeUser($user)
    {
        $this->users->removeElement($user);
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function isPublic()
    {
        return (bool) !($this->password && strlen($this->password) > 0);
    }

    public function setPublic($public)
    {
        //noop
    }

    public function getMetadata()
    {
        return $this->metadata;
    }

    public function setMetadata(LeagueMetadata $metadata)
    {
        $this->metadata = $metadata;
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
        if (!$this->pickSets->contains($pickSet)) {
            $this->pickSets[] = $pickSet;
        }
    }

    public function removePickSet(PickSet $pickSet)
    {
        if ($this->pickSets->contains($pickSet)) {
            $this->pickSets->removeElement($pickSet);
        }
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

    public function __toString()
    {
        return $this->name;
    }
}
