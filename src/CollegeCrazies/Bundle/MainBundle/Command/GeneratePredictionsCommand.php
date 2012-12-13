<?php

namespace CollegeCrazies\Bundle\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GeneratePredictionsCommand extends ContainerAwareCommand
{
    protected $generator;
    protected $numPredictions;

    protected function configure()
    {
        $this->setName('college-crazies:generate-predictions')
            ->setDescription('Generate a set of predictions for doing statistical analysis')
            ->addArgument('predictions', InputArgument::REQUIRED, 'The number of predictions to create')
            ->addOption('truncate', null, InputOption::VALUE_NONE, "Truncate the prediction tables")
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->generator = $this->getContainer()->get('prediction.generator');
        $this->numPredictions = $input->getArgument('predictions');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->generator->createPredictions($this->numPredictions, $input->getOption('truncate'));
    }
}
