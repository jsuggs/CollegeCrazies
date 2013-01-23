<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Event;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ModuleSubscriber implements EventSubscriberInterface
{
    protected $om;
    protected $year;

    public function __construct(ObjectManager $om, $year)
    {
        $this->om = $om;
        $this->year = $year;
    }

    static public function getSubscribedEvents()
    {
        return array(
            ModuleEvents::MODULE_UPDATED => array('updateModule', 0),
        );
    }

    public function updateModule(ModuleConfigEvent $event)
    {
        $module = $event->getModule();
        $module = $this->om->getRepository('SofaChampsCoreBundle:Module')->find($module->getId());

        $moduleConfig = $module->getConfigForYear($this->year);
        $moduleConfig->setStartTime($event->getStartTime());
        $moduleConfig->setEndTime($event->getEndTime());

        $om->flush();
    }
}
