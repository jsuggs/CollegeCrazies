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
        $this->importConferences($output);
        $this->importConferenceMembers($output);
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

    protected function importConferences(OutputInterface $output)
    {
        $output->writeln('Creating temporary table');
        $this->conn->exec('CREATE TEMPORARY TABLE _ncaa_conferences (LIKE ncaa_conferences)');
        $conferenceDataFile = $this->dataDirectory . '/conferences.csv';
        $this->conn->exec(sprintf("COPY _ncaa_conferences FROM '%s' CSV HEADER", $conferenceDataFile));
        $output->writeln('New data loaded into temporary table');
        $this->conn->beginTransaction();
        $this->conn->exec('LOCK TABLE ncaa_conferences IN EXCLUSIVE MODE');
        $this->conn->exec('UPDATE ncaa_conferences SET name = _t.name FROM _ncaa_conferences _t WHERE ncaa_conferences.abbr = _t.abbr');
        $this->conn->exec('INSERT INTO ncaa_conferences SELECT _t.abbr, _t.name FROM _ncaa_conferences _t LEFT OUTER JOIN ncaa_conferences t ON t.abbr = _t.abbr WHERE t.abbr IS NULL');
        $this->conn->commit();
    }

    protected function importConferenceMembers(OutputInterface $output)
    {
        $output->writeln('Creating temporary table');
        $membershipDataFiles = glob(sprintf('%s/conference_membership/*/*.csv', $this->dataDirectory));
        $this->conn->beginTransaction();
        $this->conn->exec('TRUNCATE ncaaf_conference_members');
        foreach ($membershipDataFiles as $dataFile) {
            $this->conn->exec(sprintf("COPY ncaaf_conference_members FROM '%s' CSV HEADER", $dataFile));
        }
        $this->conn->commit();
    }
}
