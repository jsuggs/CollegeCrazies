<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScorePicksCommand extends ContainerAwareCommand
{
    protected $manager;

    protected function configure()
    {
        $this->setName('sofachamps:superbowlchallenge:score-picks')
            ->setDescription('Score all of the picks')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->manager = $this->getContainer()->get('sofachamps.superbowlchallenge.pickmanager');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->manager->scorePicks(2013);
        //$this->analyzer->deleteAnalysis();
        //$progress = $this->getHelperSet()->get('progress');
        //$progress->start($output, count($this->leagues));
        //foreach ($this->leagues as $league) {
            //$this->analyzer->analyizeLeaguePickSets($league);
            //$progress->advance();
        //}
        //$progress->finish();
    }
}
