<?php

namespace SofaChamps\Bundle\CoreBundle\Event;

use SofaChamps\Bundle\CoreBundle\Entity\Module;
use SofaChamps\Bundle\CoreBundle\Entity\ModuleConfig;
use SofaChamps\Bundle\CoreBundle\Module\ModuleInterface;
use Symfony\Component\EventDispatcher\Event;

class ModuleEvent extends Event
{
    protected $module;

    public function __construct(ModuleInterface $module)
    {
        $this->module = $module;
    }

    public function getModule()
    {
        return $this->module;
    }

    public function setModuleConfig(ModuleConfig $moduleConfig)
    {
        $this->moduleConfig = $moduleConfig;
    }

    public function getModuleConfig()
    {
        return $this->moduleConfig;
    }
}
