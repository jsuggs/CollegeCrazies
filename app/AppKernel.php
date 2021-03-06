<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new FOS\FacebookBundle\FOSFacebookBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Sonata\jQueryBundle\SonatajQueryBundle(),
            new Vlabs\MediaBundle\VlabsMediaBundle(),
            new Liip\DoctrineCacheBundle\LiipDoctrineCacheBundle(),
            new Mmoreram\GearmanBundle\GearmanBundle(),
            new SofaChamps\Bundle\BowlPickemBundle\SofaChampsBowlPickemBundle(),
            new SofaChamps\Bundle\EmailBundle\SofaChampsEmailBundle(),
            new SofaChamps\Bundle\UserBundle\SofaChampsUserBundle(),
            new SofaChamps\Bundle\SuperBowlChallengeBundle\SofaChampsSuperBowlChallengeBundle(),
            new SofaChamps\Bundle\CoreBundle\SofaChampsCoreBundle(),
            new SofaChamps\Bundle\NFLBundle\SofaChampsNFLBundle(),
            new SofaChamps\Bundle\FacebookBundle\SofaChampsFacebookBundle(),
            new SofaChamps\Bundle\SquaresBundle\SofaChampsSquaresBundle(),
            new SofaChamps\Bundle\BracketBundle\SofaChampsBracketBundle(),
            new SofaChamps\Bundle\MarchMadnessBundle\SofaChampsMarchMadnessBundle(),
            new SofaChamps\Bundle\NCAABundle\SofaChampsNCAABundle(),
            new SofaChamps\Bundle\MaintenanceBundle\SofaChampsMaintenanceBundle(),
            new SofaChamps\Bundle\PriceIsRightChallengeBundle\SofaChampsPriceIsRightChallengeBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        if ('test' == $this->getEnvironment()) {
            $bundles[] = new Liip\FunctionalTestBundle\LiipFunctionalTestBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
