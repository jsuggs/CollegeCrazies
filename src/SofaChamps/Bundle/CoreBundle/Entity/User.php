<?php

namespace SofaChamps\Bundle\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use SofaChamps\Bundle\BowlPickemBundle\Entity\PickSet;
use SofaChamps\Bundle\BowlPickemBundle\Entity\League;
use Symfony\Component\Validator\Constraints as Assert;
use Vlabs\MediaBundle\Annotation\Vlabs;

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
     * @ORM\Column(name="facebookId", type="string", length=255, nullable=true)
     */
    protected $facebookId;

    /**
     * @ORM\ManyToMany(targetEntity="SofaChamps\Bundle\BowlPickemBundle\Entity\League", mappedBy="users", fetch="EXTRA_LAZY")
     */
    protected $leagues;

    /**
     * @ORM\OneToMany(targetEntity="SofaChamps\Bundle\BowlPickemBundle\Entity\PickSet", mappedBy="user", fetch="EXTRA_LAZY")
     */
    protected $pickSets;

    /**
     * @ORM\ManyToMany(targetEntity="SofaChamps\Bundle\BowlPickemBundle\Entity\League", mappedBy="commissioners", fetch="EXTRA_LAZY")
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

    /**
     * @ORM\Column(type="string", length=30, options={"default": "America/Chicago"})
     */
    protected $timezone = 'America/Chicago';

    /**
     * @ORM\OneToMany(targetEntity="\SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Pick", mappedBy="user", fetch="EXTRA_LAZY")
     */
    protected $sbcPicks;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="referrals")
     * @ORM\JoinColumn(name="referrer_id", referencedColumnName="id")
     **/
    protected $referrer;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="referrer")
     **/
    protected $referrals;

    /**
     * @ORM\OneToOne(targetEntity="Image", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(name="profile_img_id", referencedColumnName="id")
     * @Vlabs\Media(identifier="image_entity", upload_dir="uploads/user/profile")
     * @Assert\Valid
     */
    protected $profilePicture;

    /**
     * @ORM\OneToMany(targetEntity="SofaChamps\Bundle\SquaresBundle\Entity\Game", mappedBy="user")
     */
    protected $squaresGames;

    /**
     * @ORM\OneToMany(targetEntity="SofaChamps\Bundle\SquaresBundle\Entity\Player", mappedBy="user")
     */
    protected $squaresPlayers;

    public function __construct()
    {
        parent::__construct();
        $this->sbcPicks = new ArrayCollection();
        $this->pickSets = new ArrayCollection();
        $this->leagues = new ArrayCollection();
        $this->referrals = new ArrayCollection();
        $this->commissionerLeagues = new ArrayCollection();
        $this->squaresGames = new ArrayCollection();
        $this->squaresPlayers = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
        if (!$this->username) {
            $this->setUsername($facebookId);
        }
    }

    public function getFacebookId()
    {
        return $this->facebookId;
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

    public function getPickSetsForSeason($season)
    {
        return $this->pickSets->filter(function($pickSet) use ($season) {
            return $pickSet->getSeason() == $season;
        });
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

    public function getLeaguesForSeason($season)
    {
        return $this->leagues->filter(function($league) use ($season) {
            return $league->getSeason() == $season;
        });
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

    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
    }

    public function getTimezone()
    {
        return $this->timezone;
    }

    public function getSuperBowlChallengePickForYear($year)
    {
        return $this->sbcPicks->filter(function($pick) use ($year) {
            return $pick->getYear() == $year;
        })->first();
    }

    public function isBowlPickemCommissionerForSeason($season)
    {
        return $this->getCommissionerLeaguesForSeason($season)->count() > 0;
    }

    public function getCommissionerLeagues()
    {
        return $this->commissionerLeagues;
    }

    public function getCommissionerLeaguesForSeason($season)
    {
        return $this->getCommissionerLeagues()->filter(function(League $league) use ($season) {
            return $season = $league->getSeason();
        });
    }

    public function setReferrer(User $user)
    {
        $this->referrer = $user;
    }

    public function getReferrer()
    {
        return $this->referrer;
    }

    public function getReferrals()
    {
        return $this->referrals;
    }

    public function setProfilePicture(Image $profilePicture)
    {
        $this->profilePicture = $profilePicture;
    }

    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    public function getSquaresGames()
    {
        return $this->squaresGames;
    }

    public function __toString()
    {
        return $this->username;
    }
}
