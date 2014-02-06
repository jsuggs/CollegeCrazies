<?php

namespace SofaChamps\Bundle\MaintenanceBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MaintenanceCommand extends ContainerAwareCommand
{
    protected $manager;

    protected function configure()
    {
        $this->setName('sofachamps:maintenance')
            ->setDescription('Enable/Disable maintenance mode')
            ->addArgument('action', InputArgument::REQUIRED, '(enable|disable)')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->manager = $this->getContainer()->get('sofachamps.maintenance.maintenance_manager');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getArgument('action') == 'enable') {
            $this->manager->enableMaintenanceMode();
        } elseif ($input->getArgument('action') == 'disable') {
            $this->manager->disableMaintenanceMode();
        } else {
            throw new \InvalidArgumentException(sprintf('Invalid action passed ("%s") valid values are enabled|disabled', $input->getArgument('action')));
        }
    }
}
