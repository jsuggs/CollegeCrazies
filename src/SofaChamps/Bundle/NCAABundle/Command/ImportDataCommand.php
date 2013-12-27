<?php

namespace SofaChamps\Bundle\NCAABundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportDataCommand extends ContainerAwareCommand
{
    protected $conn;
    protected $dataDirectory;

    protected function configure()
    {
        $this->setName('ncaa:import-data')
            ->setDescription('Import data')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->conn = $this->getContainer()->get('doctrine.dbal.super_connection');
        $this->dataDirectory = $this->getContainer()->getParameter('kernel.root_dir') . '/data/ncaa';
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->importTeams($output);
    }

    protected function importTeams(OutputInterface $output)
    {
        $output->writeln('Creating temporary table');
        $this->conn->exec('create temporary table _ncaa_teams (like ncaa_teams)');
        $teamDataFile = $this->dataDirectory . '/teams.csv';
        $this->conn->exec(sprintf("copy _ncaa_teams from '%s' CSV HEADER", $teamDataFile));
    }
}
