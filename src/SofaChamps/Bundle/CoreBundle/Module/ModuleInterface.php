<?php

namespace SofaChamps\Bundle\CoreBundle\Module;

interface ModuleInterface
{
    public function getModuleId();
    public function getActiveConfigId();
    public function getStartTime();
    public function getEndTime();
}
