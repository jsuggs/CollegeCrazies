<?php

namespace SofaChamps\Bundle\CoreBundle\Security\Authorization\Voter;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\CoreBundle\Entity\Person;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * PersonVoter
 *
 * @DI\Service("sofachamps.core.voter.person")
 * @DI\Tag("security.voter")
 */
class PersonVoter implements VoterInterface
{
    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array('EDIT'));
    }

    public function supportsClass($class)
    {
        return $class === 'SofaChamps\Bundle\CoreBundle\Entity\Person';
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if (!$this->supportsClass(get_class($object))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        foreach ($attributes as $attribute) {
            if (!$this->supportsAttribute($attribute)) {
                continue;
            }

            $user = $token->getUser();

            if ($attribute === 'EDIT') {
                return $this->canUserEditPerson($object, $user);
            }
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }

    protected function canUserEditPerson(Person $person, User $user = null)
    {
        // TODO
        return VoterInterface::ACCESS_ABSTAIN;
    }
}
