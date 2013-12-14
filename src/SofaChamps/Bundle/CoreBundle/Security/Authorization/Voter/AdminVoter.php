<?php

namespace SofaChamps\Bundle\CoreBundle\Security\Authorization\Voter;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * This voter always returns ACCESS_GRANTED if the user is an admin
 *
 * @DI\Service("sofachamps.core.voter.admin")
 * @DI\Tag("security.voter")
 */
class AdminVoter implements VoterInterface
{
    public function supportsClass($class)
    {
        return true;
    }

    public function supportsAttribute($attribute)
    {
        return !in_array($attribute, array('ROLE_PREVIOUS_ADMIN'));
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        $result = VoterInterface::ACCESS_ABSTAIN;

        foreach ($attributes as $attribute) {
            if (!$this->supportsAttribute($attribute)) {
                continue;
            }

            foreach ($token->getRoles() as $role) {
                if ($role->getRole() == 'ROLE_ADMIN') {
                    return VoterInterface::ACCESS_GRANTED;
                }
            }
        }

        return $result;
    }
}
