<?php

namespace SofaChamps\Bundle\MarchMadnessBundle\Command;

use Doctrine\DBAL\DBALException;
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
    }

    protected function importBrackets(OutputInterface $output)
    {
        $output->writeln('Importing Brackets');
        $bracketYears = glob(sprintf('%s/*', $this->dataDirectory));
        $this->conn->beginTransaction();
        foreach ($bracketYears as $yearPath) {
            $year = basename($yearPath);
            $output->writeln(" * $year");
            try {
                $this->conn->exec(sprintf("INSERT INTO mm_brackets (season) VALUES (%d)", $year));
            } catch (DBALException $e) {
                //var_dump($e);
            }
        }
        $this->conn->commit();
    }

    protected function importRegions(OutputInterface $output)
    {
        $output->writeln('Importing Regions');
        $regionDataFiles = glob(sprintf('%s/*/regions.csv', $this->dataDirectory));
        $this->conn->beginTransaction();
        $this->conn->exec('LOCK TABLE mm_regions IN EXCLUSIVE MODE');
        $this->conn->exec('TRUNCATE mm_regions CASCADE');
        foreach ($regionDataFiles as $dataFile) {
            $output->writeln($dataFile);
            $this->conn->exec(sprintf("COPY mm_regions FROM '%s' CSV HEADER", $dataFile));
        }
        $this->conn->commit();
    }

    protected function importTeams(OutputInterface $output)
    {
        $output->writeln('Importing Teams');
        $teamDataFiles = glob(sprintf('%s/*/teams.csv', $this->dataDirectory));
        $this->conn->beginTransaction();
        $this->conn->exec('DELETE FROM mm_teams');
        foreach ($teamDataFiles as $dataFile) {
            $output->writeln($dataFile);
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
}
