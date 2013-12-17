<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Security\Authorization\Voter;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BowlPickemBundle\Entity\PickSet;
use SofaChamps\Bundle\BowlPickemBundle\Service\PicksLockedManager;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * PickSetVoter
 *
 * @DI\Service("sofachamps.bp.voter.pickset")
 * @DI\Tag("security.voter")
 */
class PickSetVoter implements VoterInterface
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
        return in_array($attribute, array('CREATE', 'EDIT', 'VIEW'));
    }

    public function supportsClass($class)
    {
        return $class === 'SofaChamps\Bundle\BowlPickemBundle\Entity\PickSet';
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        // Only vote on PickSets
        if (!$this->supportsClass(get_class($object))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        foreach ($attributes as $attribute) {
            if (!$this->supportsAttribute($attribute)) {
                continue;
            }

            $user = $token->getUser();

            if ($attribute === 'VIEW') {
                return $this->canUserViewPickSet($user, $object);
            } elseif ($attribute === 'EDIT') {
                return $this->canUserEditPickSet($user, $object);
            } elseif ($attribute === 'CREATE') {
                return $this->canUserCreatePickSet();
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

    protected function canUserViewPickSet(User $user, PickSet $pickSet)
    {
        if ($pickSet->getUser() == $user) {
            return VoterInterface::ACCESS_GRANTED;
        }

        return $this->picksLockedManager->arePickLocked()
            ? VoterInterface::ACCESS_GRANTED
            : VoterInterface::ACCESS_DENIED;
    }

    protected function canUserEditPickSet(User $user, PickSet $pickSet)
    {
        if ($pickSet->getUser() == $user) {
            return VoterInterface::ACCESS_GRANTED;
        }

        return $pickSet->getUser() == $user
            ? VoterInterface::ACCESS_GRANTED
            : VoterInterface::ACCESS_DENIED;
    }
}
