<?php

namespace SofaChamps\Bundle\FacebookBundle\Security\User\Provider;

use \BaseFacebook;
use \FacebookApiException;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * FacebookProvider
 *
 * @DI\Service("sofachamps.facebook.user")
 */
class FacebookProvider implements UserProviderInterface
{
    protected $facebook;
    protected $userManager;
    protected $validator;

    /**
     * @DI\InjectParams({
     *      "facebook" = @DI\Inject("fos_facebook.api"),
     *      "userManager" = @DI\Inject("fos_user.user_manager"),
     *      "validator" = @DI\Inject("validator"),
     * })
     */
    public function __construct(BaseFacebook $facebook, $userManager, $validator)
    {
        $this->facebook;
        $this->userManager;
        $this->validator;
    }

    public function supportsClass($class)
    {
        return $this->userManager->supportsClass($class);
    }

    public function findUserByFbId($fbId)
    {
        return $this->userManager->findUserBy(array('facebookId' => $fbId));
    }

    public function loadUserByUsername($username)
    {
        $user = $this->findUserByFbId($username);

        try {
            $fbdata = $this->facebook->api('/me');
        } catch (FacebookApiException $e) {
            $fbdata = null;
        }

        if (!empty($fbdata)) {
            if (empty($user)) {
                $user = $this->userManager->createUser();
                $user->setEnabled(true);
                $user->setPassword('');
            }

            // TODO use http://developers.facebook.com/docs/api/realtime
            $user->setFBData($fbdata);

            if (count($this->validator->validate($user, 'Facebook'))) {
                // TODO: the user was found obviously, but doesnt match our expectations, do something smart
                throw new UsernameNotFoundException('The facebook user could not be stored');
            }
            $this->userManager->updateUser($user);
        }

        if (empty($user)) {
            throw new UsernameNotFoundException('The user is not authenticated on facebook');
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$this->supportsClass(get_class($user)) || !$user->getFacebookId()) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getFacebookId());
    }
}
