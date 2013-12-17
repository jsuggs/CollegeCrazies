<?php

namespace SofaChamps\Bundle\NCAABundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportDataCommand extends ContainerAwareCommand
{
    protected $dataDirectory;

    protected function configure()
    {
        $this->setName('ncaa:import-data')
            ->setDescription('Import data')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->dataDirectory = $this->getContainer()->getParameter('kernel.root_dir') . '/data/ncaa';
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->importTeams($output);
    }

    protected function importTeams(OutputInterface $output)
    {
        $output->writeln(sprintf('psql -U %s'));
    }
}
