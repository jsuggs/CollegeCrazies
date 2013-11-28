<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Security\Authorization\Voter;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BowlPickemBundle\Entity\League;
use SofaChamps\Bundle\BowlPickemBundle\Entity\PickSet;
use SofaChamps\Bundle\BowlPickemBundle\Service\PicksLockedManager;
use SofaChamps\Bundle\CoreBundle\Entity\User;
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

    /**
     * @DI\InjectParams({
     *      "picksLockedManager" = @DI\Inject("sofachamps.bp.picks_locked_manager"),
     * })
     */
    public function __construct(PicksLockedManager $picksLockedManager)
    {
        $this->picksLockedManager = $picksLockedManager;
    }

    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array('VIEW', 'VIEW_PICKS', 'MANAGE'));
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

            if ($attribute === 'VIEW') {
                return $this->canUserViewLeague($user, $object);
            } elseif ($attribute === 'VIEW_PICKS') {
                return $this->canUserViewLeaguePicks($user, $object);
            } elseif ($attribute === 'CREATE') {
                return $this->canUserCreatePickSet();
            } elseif ($attribute === 'MANAGE') {
                return $this->canUserManageLeague($user, $object);
            }

            if ($object->getUser() == $user) {
                return VoterInterface::ACCESS_GRANTED;
            }
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }

    protected function canUserCreatePickSet()
    {
        return $this->picksLockedManager->arePickLocked()
            ? VoterInterface::ACCESS_DENIED
            : VoterInterface::ACCESS_GRANTED;
    }

    protected function canUserViewLeague(User $user, League $league)
    {
        if ($league->isPublic()) {
            return VoterInterface::ACCESS_GRANTED;
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

    protected function canUserViewLeaguePicks(User $user, League $league)
    {
        return $this->picksLockedManager->arePickLocked()
            ? VoterInterface::ACCESS_GRANTED
            : VoterInterface::ACCESS_DENIED;
    }
}
