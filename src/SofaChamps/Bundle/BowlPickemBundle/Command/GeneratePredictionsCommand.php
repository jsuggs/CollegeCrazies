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
            ->addArgument('predictions', InputArgument::REQUIRED, 'The number of predictions to create')
            ->addArgument('season', InputArgument::REQUIRED, 'The season to create predictions for')
            ->addOption('truncate', null, InputOption::VALUE_NONE, "Truncate the prediction tables")
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->generator = $this->getContainer()->get('sofachamps.bp.prediction_generator');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->generator->createPredictions($input->getArgument('predictions'), $input->getArgument('season'), $input->getOption('truncate'));
    }
}
