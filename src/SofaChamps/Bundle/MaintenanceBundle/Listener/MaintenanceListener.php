<?php

namespace SofaChamps\Bundle\MaintenanceBundle\Listener;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\MaintenanceBundle\Maintenance\MaintenanceManager;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @DI\Service("sofachamps.maintenance.maintenance_listener")
 */
class MaintenanceListener
{
    protected $manager;
    protected $twig;

    /**
     * @DI\InjectParams({
     *      "manager" = @DI\Inject("sofachamps.maintenance.maintenance_manager"),
     *      "twig" = @DI\Inject("templating"),
     * })
     */
    public function __construct(MaintenanceManager $manager, TwigEngine $twig)
    {
        $this->manager = $manager;
        $this->twig = $twig;
    }

    /**
     * @DI\Observe("kernel.request", priority=255)
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        // Only process the master request
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        if ($this->manager->showMaintenance()) {
            $content = $this->twig->render('SofaChampsMaintenanceBundle::maintenance.html.twig');
            $event->setResponse(new Response($content, 503));
            $event->stopPropagation();
        }
    }
}
