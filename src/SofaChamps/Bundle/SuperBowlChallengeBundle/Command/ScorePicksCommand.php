<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScorePicksCommand extends ContainerAwareCommand
{
    protected $manager;
    protected $year;

    protected function configure()
    {
        $this->setName('superbowlchallenge:score-picks')
            ->addArgument('year', InputArgument::REQUIRED, 'The year to calculat the scores for')
            ->setDescription('Score all of the picks')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->manager = $this->getContainer()->get('sofachamps.superbowlchallenge.pickmanager');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->manager->scorePicks($input->getArgument('year'));
    }
}
