<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SeasonCopyCommand extends ContainerAwareCommand
{
    protected $copier;
    protected $fromSeason;
    protected $season;

    protected function configure()
    {
        $this->setName('bowl-pickem:copy-season')
            ->setDescription('Copy season data')
            ->addArgument('from-season', InputArgument::REQUIRED, 'The season to copy from')
            ->addArgument('season', InputArgument::REQUIRED, 'The new season')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->copier = $this->getContainer()->get('sofachamps.bp.season_copier');
        $repo = $this->getContainer()
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('SofaChampsBowlPickemBundle:Season');

        $this->fromSeason = $repo->find($input->getArgument('from-season'));
        $this->season = $repo->find($input->getArgument('season'));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $progress = $this->getHelperSet()->get('progress');
        $progress->start($output, 1);
        $this->copier->copyGames($this->fromSeason, $this->season);
        $progress->advance();
        $progress->finish();
    }
}
