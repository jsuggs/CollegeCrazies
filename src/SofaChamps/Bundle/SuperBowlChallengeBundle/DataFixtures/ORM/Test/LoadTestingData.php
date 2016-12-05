<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\DataFixtures\ORM\Test;

use SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Config;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadTestingData implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $config = new Config('2014');
        $config->setStartTime(new \DateTime());
        $config->setCloseTime(new \DateTime());
        $config->setScoresCalculated(false);
        $config->setFinalScorePoints(100);
        $config->setHalftimeScorePoints(50);
        $config->setFirstTeamToScoreInAQuarterPoints(25);
        $config->setNeitherTeamToScoreInAQuarterPoints(35);
        $config->setBonusQuestionPoints(15);
        $manager->persist($config);
        $manager->flush();
    }
}
