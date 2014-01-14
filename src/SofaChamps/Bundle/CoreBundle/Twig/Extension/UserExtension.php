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

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('user_date', array($this, 'getUserDate')),
        );
    }

    public function getUserDate($date, $format)
    {
        $date = is_string($date)
            ? new \DateTime($date)
            : $date;

        $date = (bool) $this->getLoggedInUser()
            ? $date->setTimezone(new \DateTimeZone($this->getLoggedInUser()->getTimezone()))
            : $date;

        return $date->format($format);
    }

    public function profileLink(User $user, $showProfilePicture = false)
    {
        return $this->container->get('templating')->render('SofaChampsCoreBundle:User:_profile_link.html.twig', array(
            'user' => $user,
            'showProfilePicture' => $showProfilePicture,
        ));
    }

    protected function getSecurityContext()
    {
        return $this->container->get('security.context');
    }

    public function getLoggedInUser()
    {
        return $this->getSecurityContext()->getToken() && $this->getSecurityContext()->isGranted('ROLE_USER')
            ? $this->getSecurityContext()->getToken()->getUser()
            : null;
    }

    public function getName()
    {
        return 'sofachamps.user';
    }
}
