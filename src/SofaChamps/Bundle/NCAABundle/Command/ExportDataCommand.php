<?php

namespace SofaChamps\Bundle\NCAABundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportDataCommand extends ContainerAwareCommand
{
    protected $conn;
    protected $dataDirectory;

    protected function configure()
    {
        $this->setName('ncaa:export-data')
            ->setDescription('Export data')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->conn = $this->getContainer()->get('doctrine.dbal.super_connection');
        $this->dataDirectory = $this->getContainer()->getParameter('kernel.root_dir') . '/data/ncaa';
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->exportTeams($output);
    }

    protected function exportTeams(OutputInterface $output)
    {
        $teamDataFile = $this->dataDirectory . '/teams.csv';
        $this->conn->exec(sprintf("COPY (SELECT id, name, thumbnail FROM ncaa_teams ORDER BY id) TO '%s' CSV HEADER", $teamDataFile));
    }
}
