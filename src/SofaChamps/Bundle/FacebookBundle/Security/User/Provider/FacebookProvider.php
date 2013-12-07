<?php

namespace SofaChamps\Bundle\FacebookBundle\Security\User\Provider;

use \BaseFacebook;
use \FacebookApiException;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\FacebookBundle\User\FacebookUserManager;
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
    protected $facebookUserManager;

    /**
     * @DI\InjectParams({
     *      "facebook" = @DI\Inject("fos_facebook.api"),
     *      "userManager" = @DI\Inject("fos_user.user_manager"),
     *      "validator" = @DI\Inject("validator"),
     *      "facebookUserManager" = @DI\Inject("sofachamps.facebook.user_manager"),
     * })
     */
    public function __construct(BaseFacebook $facebook, $userManager, $validator, FacebookUserManager $facebookUserManager)
    {
        $this->facebook = $facebook;
        $this->userManager = $userManager;
        $this->validator = $validator;
        $this->facebookUserManager = $facebookUserManager;
    }

    public function supportsClass($class)
    {
        return $this->userManager->supportsClass($class);
    }

    public function findUserByFbId($fbId)
    {
        return $this->userManager->findUserBy(array('facebookId' => $fbId));
    }

    public function findUserByEmail($email)
    {
        return $this->userManager->findUserBy(array('emailCanonical' => $email));
    }

    public function loadUserByUsername($username)
    {
        $user = $this->findUserByFbId($username);

        try {
            $fbData = $this->facebook->api('/me');
        } catch (FacebookApiException $e) {
            $fbData = null;
        }

        if (!empty($fbData)) {
            // See if a user with this email already exists
            if (empty($user) && isset($fbData['email'])) {
                $user = $this->findUserByEmail($fbData['email']);
            }

            // If not found via fbId or email, then create
            if (empty($user)) {
                $user = $this->userManager->createUser();
                $user->setEnabled(true);
                $user->setPassword('');
            }

            $this->facebookUserManager->updateUserWithFacebookData($user, $fbData);

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
