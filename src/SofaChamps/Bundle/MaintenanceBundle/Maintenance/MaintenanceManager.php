<?php

namespace SofaChamps\Bundle\MaintenanceBundle\Maintenance;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * MaintenanceManager
 *
 * Currently a very poor mans implementation
 *
 * @DI\Service("sofachamps.maintenance.maintenance_manager")
 */
class MaintenanceManager
{
    private $filePath;
    private $env;

    /**
     * @DI\InjectParams({
     *      "filePath" = @DI\Inject("%sofachamps.maintenance.file_path%"),
     *      "env" = @DI\Inject("%kernel.environment%"),
     * })
     */
    public function __construct($filePath, $env)
    {
        $this->filePath = $filePath;
        $this->env = $env;
    }

    public function enableMaintenanceMode()
    {
        file_put_contents($this->filePath, time());
    }

    public function disableMaintenanceMode()
    {
        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }
    }

    public function isMaintenanceMode()
    {
        return file_exists($this->filePath);
    }

    public function showMaintenance()
    {
        return !in_array($this->env, array('dev', 'test')) && $this->isMaintenanceMode();
    }
}
