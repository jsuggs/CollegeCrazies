<?php

namespace SofaChamps\Bundle\CoreBundle\Twig\Extension;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * UserExtension
 *
 * @DI\Service("sofachamps.core.twig.user")
 * @DI\Tag("twig.extension")
 */
class UserExtension extends \Twig_Extension
{
    private $container;

    /**
     * @DI\InjectParams({
     *      "container" = @DI\Inject("service_container")
     * })
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            'profile_link' => new \Twig_Function_Method($this, 'profileLink'),
        );
    }

    public function profileLink(User $user)
    {
        return $this->container->get('templating')->render('SofaChampsCoreBundle:User:_profile_link.html.twig', array(
            'user' => $user,
        ));
    }

    public function getName()
    {
        return 'sofachamps.user';
    }
}
