<?php

namespace SofaChamps\Bundle\SquaresBundle\Security\Authorization\Voter;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\SquaresBundle\Entity\Player;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * PlayerVoter
 *
 * @DI\Service("sofachamps.squares.voter.player")
 * @DI\Tag("security.voter")
 */
class PlayerVoter implements VoterInterface
{
    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array('EDIT'));
    }

    public function supportsClass($class)
    {
        return $class === 'SofaChamps\Bundle\SquaresBundle\Entity\Player';
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

            if ($attribute === 'EDIT') {
                return $this->canUserEditPlayer($user, $object);
            }
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }

    protected function canUserEditPlayer(User $user, Player $player)
    {
        $game = $player->getGame();
        $userPlayer = $game->getPlayerForUser($user);

        return !$game->isLocked() && (($userPlayer && $userPlayer->isAdmin()) || $player->getUser() == $user)
            ? VoterInterface::ACCESS_GRANTED
            : VoterInterface::ACCESS_DENIED;
    }
}
