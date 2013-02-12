<?php

namespace SofaChamps\Bundle\NCAAMBundle\DataFixtures\ORM\Test;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SofaChamps\Bundle\NCAAMBundle\Entity\Team;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadTeamData implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        // Teams
        $sharks = new Team();
        $manager->persist($sharks);
        $manager->flush();
    }
}
