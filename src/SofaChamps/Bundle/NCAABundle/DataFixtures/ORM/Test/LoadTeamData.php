<?php

namespace SofaChamps\Bundle\NCAABundle\DataFixtures\ORM\Test;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SofaChamps\Bundle\NCAABundle\Entity\NCAAFConference;
use SofaChamps\Bundle\NCAABundle\Entity\NCAAFConferenceMember;
use SofaChamps\Bundle\NCAABundle\Entity\Team;
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
        $alabama = new Team();
        $alabama->setId('ALA');
        $alabama->setName('Alabama');
        $alabama->setThumbnail('');

        $manager->persist($alabama);

        // Conferences
        $sec = new NCAAFConference();
        $sec->setAbbr('SEC');
        $sec->setName('South Eastern Conference');

        $manager->persist($sec);

        $alaSec2013 = new NCAAFConferenceMember();
        $alaSec2013->setTeam($alabama);
        $alaSec2013->setConference($sec);
        $alaSec2013->setSeason(2013);

        $manager->persist($alaSec2013);

        $manager->flush();
    }
}
