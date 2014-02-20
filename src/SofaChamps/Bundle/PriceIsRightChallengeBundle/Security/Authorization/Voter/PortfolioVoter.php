<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Security\Authorization\Voter;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Portfolio;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * @DI\Service("sofachamps.pirc.voter.portfolio")
 * @DI\Tag("security.voter")
 */
class PortfolioVoter implements VoterInterface
{
    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array('EDIT', 'VIEW'));
    }

    public function supportsClass($class)
    {
        return $class === 'SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Portfolio';
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        // Only vote on Portfolios
        if (!$this->supportsClass(get_class($object))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        foreach ($attributes as $attribute) {
            if (!$this->supportsAttribute($attribute)) {
                continue;
            }

            $user = $token->getUser();

            if ($attribute === 'VIEW') {
                return $this->canUserViewPortfolio($user, $object);
            } elseif ($attribute === 'EDIT') {
                return $this->canUserEditPortfolio($user, $object);
            }

            if ($object->getUser() == $user) {
                return VoterInterface::ACCESS_GRANTED;
            }
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }

    protected function canUserViewPortfolio(User $user, Portfolio $portfolio)
    {
        return $user == $portfolio->getUser() || (($game = $portfolio->getGame()) && !$game->isLocked())
            ? VoterInterface::ACCESS_GRANTED
            : VoterInterface::ACCESS_DENIED;
    }

    protected function canUserEditPortfolio(User $user, Portfolio $portfolio)
    {
        return $user == $portfolio->getUser() || (($game = $portfolio->getGame()) && $game->isManager($user))
            ? VoterInterface::ACCESS_GRANTED
            : VoterInterface::ACCESS_DENIED;
    }
}
