<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AnalyzeLeagueCommand extends ContainerAwareCommand
{
    protected $analyzer;
    protected $leagues;

    protected function configure()
    {
        $this->setName('college-crazies:analyze-picksets')
            ->setDescription('Analyize all picksets in their respective leagues')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->analyzer = $this->getContainer()->get('sofachamps.bp.pickset_analyzer');
        $this->leagues = $this->getContainer()->get('doctrine.orm.default_entity_manager')->getRepository('SofaChampsBowlPickemBundle:League')->findAll();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->analyzer->deleteAnalysis();
        $progress = $this->getHelperSet()->get('progress');
        $progress->start($output, count($this->leagues));
        foreach ($this->leagues as $league) {
            $this->analyzer->analyizeLeaguePickSets($league);
            $progress->advance();
        }
        $progress->finish();
    }
}
