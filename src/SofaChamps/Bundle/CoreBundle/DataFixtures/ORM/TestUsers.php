<?php

namespace SofaChamps\Bundle\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TestUsers implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');

        $user = new User();
        $user->setUsername('user');
        $user->setEmail('user@sofachamps.com');
        $user->setPlainPassword('userpass');
        $user->setEnabled(true);

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@sofachamps.com');
        $admin->setPlainPassword('adminpass');
        $admin->setEnabled(true);
        $admin->setRoles(array('ROLE_ADMIN'));

        $userManager->updateUser($user, true);
        $userManager->updateUser($admin, true);

        $manager->persist($user);
        $manager->persist($admin);
        $manager->flush();
    }
}
