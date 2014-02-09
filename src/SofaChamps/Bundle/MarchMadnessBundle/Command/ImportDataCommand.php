<?php

namespace SofaChamps\Bundle\MarchMadnessBundle\Command;

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
        $this->setName('march-madness:import-data')
            ->setDescription('Import data')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->conn = $this->getContainer()->get('doctrine.dbal.super_connection');
        $this->dataDirectory = $this->getContainer()->getParameter('kernel.root_dir') . '/data/mm';
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->importBrackets($output);
        $this->importRegions($output);
        $this->importTeams($output);
        $this->importGames($output);
        //$this->importConferenceMembers($output);
    }

    protected function importBrackets(OutputInterface $output)
    {
        $bracketYears = glob(sprintf('%s/*', $this->dataDirectory));
        $this->conn->beginTransaction();
        $this->conn->exec('TRUNCATE mm_brackets CASCADE');
        foreach ($bracketYears as $yearPath) {
            $year = basename($yearPath);
            $this->conn->exec(sprintf("INSERT INTO mm_brackets (season) VALUES (%d)", $year));
        }
        $this->conn->commit();
    }

    protected function importRegions(OutputInterface $output)
    {
        $regionDataFiles = glob(sprintf('%s/*/regions.csv', $this->dataDirectory));
        $this->conn->beginTransaction();
        $this->conn->exec('DELETE FROM mm_regions');
        foreach ($regionDataFiles as $dataFile) {
            $this->conn->exec(sprintf("COPY mm_regions FROM '%s' CSV HEADER", $dataFile));
        }
        $this->conn->commit();
    }

    protected function importTeams(OutputInterface $output)
    {
        $teamDataFiles = glob(sprintf('%s/*/teams.csv', $this->dataDirectory));
        $this->conn->beginTransaction();
        $this->conn->exec('DELETE FROM mm_teams');
        foreach ($teamDataFiles as $dataFile) {
            $this->conn->exec(sprintf("COPY mm_teams FROM '%s' CSV HEADER", $dataFile));
        }
        $this->conn->commit();
    }

    protected function importGames(OutputInterface $output)
    {
        $gameDataFiles = glob(sprintf('%s/*/games.csv', $this->dataDirectory));
        $this->conn->beginTransaction();
        $this->conn->exec('DELETE FROM mm_games');
        foreach ($gameDataFiles as $dataFile) {
            $this->conn->exec(sprintf("COPY mm_games FROM '%s' CSV HEADER", $dataFile));
        }
        $this->conn->commit();
    }

    protected function importConferenceMembers(OutputInterface $output)
    {
        $membershipDataFiles = glob(sprintf('%s/conference_membership/*/*.csv', $this->dataDirectory));
        $this->conn->beginTransaction();
        $this->conn->exec('TRUNCATE ncaaf_conference_members');
        foreach ($membershipDataFiles as $dataFile) {
            $this->conn->exec(sprintf("COPY ncaaf_conference_members FROM '%s' CSV HEADER", $dataFile));
        }
        $this->conn->commit();
    }
}
