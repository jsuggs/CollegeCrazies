<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Security\Authorization\Voter;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Game;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * GameVoter
 *
 * @DI\Service("sofachamps.pirc.voter.game")
 * @DI\Tag("security.voter")
 */
class GameVoter implements VoterInterface
{
    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array('EDIT', 'VIEW'));
    }

    public function supportsClass($class)
    {
        return $class === 'SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Game';
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        // Only vote on Games
        if (!$this->supportsClass(get_class($object))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        foreach ($attributes as $attribute) {
            if (!$this->supportsAttribute($attribute)) {
                continue;
            }

            $user = $token->getUser();

            if ($attribute === 'VIEW') {
                return $this->canUserViewGame($user, $object);
            } elseif ($attribute === 'EDIT') {
                return $this->canUserEditGame($user, $object);
            }

            if ($object->getUser() == $user) {
                return VoterInterface::ACCESS_GRANTED;
            }
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }

    protected function canUserViewGame(User $user, Game $game)
    {
        return VoterInterface::ACCESS_GRANTED;
    }

    protected function canUserClaimSquare(User $user, Game $game)
    {
        $player = $game->getPlayerForUser($user);

        return !$game->isLocked() && $player
            ? VoterInterface::ACCESS_GRANTED
            : VoterInterface::ACCESS_DENIED;
    }

    protected function canUserEditGame(User $user, Game $game)
    {
        return $game->isManager($user)
            ? VoterInterface::ACCESS_GRANTED
            : VoterInterface::ACCESS_DENIED;
    }
}
