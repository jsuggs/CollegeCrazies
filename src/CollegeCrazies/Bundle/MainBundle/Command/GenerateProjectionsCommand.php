<?php

namespace CollegeCrazies\Bundle\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateProjectionsCommand extends ContainerAwareCommand
{
    protected $generator;
    protected $numProjections;

    protected function configure()
    {
        $this->setName('college-crazies:generate-projections')
            ->setDescription('Generate a set of projections for doing statistical analysis')
            ->addArgument('projections', InputArgument::REQUIRED, 'The number of projections to create')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->generator = $this->getContainer()->get('projection.generator');
        $this->numProjections = $input->getArgument('projections');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->generator->createProjections($this->numProjections);
        $output->writeln('done');
    }
}
