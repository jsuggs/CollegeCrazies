<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Security\Authorization\Voter;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Pick;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Pick\PickManager;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * PickVoter
 *
 * @DI\Service("sofachamps.sbc.voter.pick")
 * @DI\Tag("security.voter")
 */
class PickVoter implements VoterInterface
{
    private $pickManager;

    /**
     * @DI\InjectParams({
     *      "pickManager" = @DI\Inject("sofachamps.superbowlchallenge.pickmanager"),
     * })
     */
    public function __construct(PickManager $pickManager)
    {
        $this->pickManager = $pickManager;
    }

    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array('CREATE', 'EDIT', 'VIEW'));
    }

    public function supportsClass($class)
    {
        return $class === 'SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Pick';
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        // Only vote on Picks
        if (!$this->supportsClass(get_class($object))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        foreach ($attributes as $attribute) {
            if (!$this->supportsAttribute($attribute)) {
                continue;
            }

            $user = $token->getUser();

            if ($attribute === 'VIEW') {
                return $this->canUserViewPick($user, $object);
            } elseif ($attribute === 'EDIT') {
                return $this->canUserEditPick($user, $object);
            } elseif ($attribute === 'CREATE') {
                return $this->canUserCreatePick($object);
            }

            if ($object->getUser() == $user) {
                return VoterInterface::ACCESS_GRANTED;
            }
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }

    protected function canUserCreatePick(Pick $pick)
    {
        return $this->picksOpen($pick)
            ? VoterInterface::ACCESS_GRANTED
            : VoterInterface::ACCESS_DENIED;
    }

    protected function canUserViewPick(User $user, Pick $pick)
    {
        if ($pick->getUser() == $user) {
            return VoterInterface::ACCESS_GRANTED;
        }

        return $this->picksOpen($pick)
            ? VoterInterface::ACCESS_DENIED
            : VoterInterface::ACCESS_GRANTED;
    }

    protected function canUserEditPick(User $user, Pick $pick)
    {
        return $pick->getUser() == $user && $this->picksEditable($pick)
            ? VoterInterface::ACCESS_GRANTED
            : VoterInterface::ACCESS_DENIED;
    }

    private function picksOpen(Pick $pick)
    {
        return $this->pickManager->picksOpen($pick->getYear());
    }

    private function picksEditable(Pick $pick)
    {
        return $this->pickManager->picksEditable($pick->getYear());
    }
}
