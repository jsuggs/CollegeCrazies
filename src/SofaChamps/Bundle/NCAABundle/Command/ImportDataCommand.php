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
        $this->conn->exec('CREATE TEMPORARY TABLE _ncaa_teams (LIKE ncaa_teams)');
        $teamDataFile = $this->dataDirectory . '/teams.csv';
        $this->conn->exec(sprintf("COPY _ncaa_teams FROM '%s' CSV HEADER", $teamDataFile));
        $output->writeln('New data loaded into temporary table');
        $this->conn->beginTransaction();
        $this->conn->exec('LOCK TABLE ncaa_teams IN EXCLUSIVE MODE');
        $this->conn->exec('UPDATE ncaa_teams SET name = _t.name, thumbnail = _t.thumbnail FROM _ncaa_teams _t WHERE ncaa_teams.id = _t.id');
        $this->conn->exec('INSERT INTO ncaa_teams SELECT _t.id, _t.name, _t.thumbnail FROM _ncaa_teams _t LEFT OUTER JOIN ncaa_teams t ON t.id = _t.id WHERE t.id IS NULL');
        $this->conn->commit();
    }
}
