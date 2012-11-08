<?php

namespace CollegeCrazies\Bundle\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AnalyzeLeagueCommand extends ContainerAwareCommand
{
    protected $analyzer;
    protected $league;

    protected function configure()
    {
        $this->setName('college-crazies:analyze-league')
            ->setDescription('Analyize a league')
            ->addArgument('league', InputArgument::REQUIRED, 'The leagueId')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->analyzer = $this->getContainer()->get('pickset.analyzer');

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $this->league = $em->getRepository('CollegeCraziesMainBundle:League')->find($input->getArgument('league'));
        if (!$this->league) {
            throw new \RunTimeException(sprintf('There was no leage with id "%s"', $input->getArgument('league')));
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('Analyizing "%s"', $this->league->getName()));
        $this->analyzer->analyizeLeaguePickSets($this->league);
        $output->writeln('done');
    }
}
