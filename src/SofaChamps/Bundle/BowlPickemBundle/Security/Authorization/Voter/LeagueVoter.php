<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Security\Authorization\Voter;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BowlPickemBundle\Entity\League;
use SofaChamps\Bundle\BowlPickemBundle\Entity\PickSet;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Season;
use SofaChamps\Bundle\BowlPickemBundle\Season\SeasonManager;
use SofaChamps\Bundle\BowlPickemBundle\Service\PicksLockedManager;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * LeagueVoter
 *
 * @DI\Service("sofachamps.bp.voter.league")
 * @DI\Tag("security.voter")
 */
class LeagueVoter implements VoterInterface
{
    private $picksLockedManager;
    private $session;

    /**
     * @DI\InjectParams({
     *      "picksLockedManager" = @DI\Inject("sofachamps.bp.picks_locked_manager"),
     *      "seasonManager" = @DI\Inject("sofachamps.bp.season_manager"),
     *      "session" = @DI\Inject("session"),
     * })
     */
    public function __construct(PicksLockedManager $picksLockedManager, SeasonManager $seasonManager, Session $session)
    {
        $this->picksLockedManager = $picksLockedManager;
        $this->seasonManager = $seasonManager;
        $this->session = $session;
    }

    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array('VIEW', 'VIEW_PICKS', 'MANAGE', 'MEMBER', 'JOIN', 'LEAVE', 'STATS'));
    }

    public function supportsClass($class)
    {
        return $class === 'SofaChamps\Bundle\BowlPickemBundle\Entity\League';
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        // Only vote on Leagues
        if (!$this->supportsClass(get_class($object))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        foreach ($attributes as $attribute) {
            if (!$this->supportsAttribute($attribute)) {
                continue;
            }

            $user = $token->getUser();
            if (!$user instanceof User) {
                $user = null;
            }

            $season = $object->getSeason() ?: $this->seasonManager->getCurrentSeason();

            if ($attribute === 'VIEW') {
                return $this->canUserViewLeague($user, $object);
            } elseif ($attribute === 'MEMBER') {
                return $this->isUserMember($user, $object);
            } elseif ($attribute === 'VIEW_PICKS') {
                return $this->canUserViewLeaguePicks($user, $object);
            } elseif ($attribute === 'CREATE') {
                return $this->canUserCreatePickSet($season);
            } elseif ($attribute === 'MANAGE') {
                return $this->canUserManageLeague($user, $object);
            } elseif ($attribute === 'JOIN') {
                return $this->canUserJoinLeague($user, $season, $object);
            } elseif ($attribute === 'LEAVE') {
                return $this->canUserLeaveLeague($user, $season, $object);
            } elseif ($attribute === 'STATS') {
                return $this->canUserViewStats($user, $object);
            }

            if ($object->getUser() == $user) {
                return VoterInterface::ACCESS_GRANTED;
            }
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }

    protected function canUserCreatePickSet(Season $season)
    {
        return $this->picksLockedManager->arePickLocked($season)
            ? VoterInterface::ACCESS_DENIED
            : VoterInterface::ACCESS_GRANTED;
    }

    protected function canUserJoinLeague(User $user, Season $season, League $league = null)
    {
        return $this->canUserCreatePickSet($season);
    }

    protected function canUserViewStats(User $user = null, League $league)
    {
        if (!$user) {
            return VoterInterface::ACCESS_DENIED;
        }

        return $user->isInTheLeague($league)
            ? VoterInterface::ACCESS_GRANTED
            : VoterInterface::ACCESS_DENIED;
    }

    protected function canUserLeaveLeague(User $user, Season $season, League $league = null)
    {
        return $this->canUserCreatePickSet($season);
    }

    protected function canUserViewLeague(User $user = null, League $league)
    {
        if ($league->isPublic()) {
            return VoterInterface::ACCESS_GRANTED;
        }

        if (!$user || !$league->isUserInLeague($user)) {
            $this->addMessage('danger', 'This is a private league');
            return VoterInterface::ACCESS_DENIED;
        }

        return VoterInterface::ACCESS_GRANTED;
    }

    protected function isUserMember(User $user = null, League $league)
    {
        if (!$user) {
            return VoterInterface::ACCESS_DENIED;
        }

        return $league->isUserInLeague($user)
            ? VoterInterface::ACCESS_GRANTED
            : VoterInterface::ACCESS_DENIED;
    }

    protected function canUserManageLeague(User $user, League $league)
    {
        return $league->userIsCommissioner($user)
            ? VoterInterface::ACCESS_GRANTED
            : VoterInterface::ACCESS_DENIED;
    }

    protected function canUserViewLeaguePicks(User $user = null, League $league)
    {
        if (!$this->picksLockedManager->arePickLocked($league->getSeason())) {
            return VoterInterface::ACCESS_DENIED;
        }

        if (!$league->isPublic() && !$league->isUserInLeague($user)) {
            return VoterInterface::ACCESS_DENIED;
        }

        return VoterInterface::ACCESS_GRANTED;
    }

    protected function addMessage($type, $message)
    {
        $this->session->getFlashBag()->add($type, $message);
    }
}
