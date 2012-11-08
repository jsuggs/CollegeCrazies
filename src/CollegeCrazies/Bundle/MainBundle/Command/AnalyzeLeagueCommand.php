<?php

namespace CollegeCrazies\Bundle\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AnalyzeLeagueCommand extends ContainerAwareCommand
{
    protected $analyzer;

    protected function configure()
    {
        $this->setName('college-crazies:analyze-picksets')
            ->setDescription('Analyize all picksets in their respective leagues')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->analyzer = $this->getContainer()->get('pickset.analyzer');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->analyzer->analyizePickSets();
    }
}
