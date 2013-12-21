<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GeneratePredictionsCommand extends ContainerAwareCommand
{
    protected $generator;

    protected function configure()
    {
        $this->setName('bowl-pickem:generate-predictions')
            ->setDescription('Generate a set of predictions for doing statistical analysis')
            ->addArgument('season', InputArgument::REQUIRED, 'The season to create predictions for')
            ->addArgument('predictions', InputArgument::OPTIONAL, 'The number of predictions to create')
            ->addOption('truncate', null, InputOption::VALUE_NONE, "Truncate the prediction tables")
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->generator = $this->getContainer()->get('sofachamps.bp.prediction_generator');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('truncate')) {
            $this->generator->truncatePredictionTables($input->getArgument('season'));
        } else {
            $this->generator->createPredictions($input->getArgument('predictions'), $input->getArgument('season'));
        }
    }
}
