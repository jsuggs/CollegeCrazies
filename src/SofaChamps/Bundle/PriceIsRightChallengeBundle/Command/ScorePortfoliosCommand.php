<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScorePortfoliosCommand extends ContainerAwareCommand
{
    const PORTFOLIO_DQL =<<<DQL
SELECT p, g, c, b
FROM SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Portfolio p
JOIN p.game g
JOIN g.bracket b
JOIN g.config c
WHERE b.season = :season
DQL;

    protected $em;
    protected $scorer;
    protected $portfolios;

    protected function configure()
    {
        $this->setName('price-is-right-challenge:score-portfolios')
            ->setDescription('Score all of the portfolios for a season')
            ->addArgument('season', InputArgument::REQUIRED, 'The season to analyze')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $this->scorer = $this->getContainer()->get('sofachamps.pirc.portfolio_scorer');
        $this->portfolios = $this->em->createQuery(self::PORTFOLIO_DQL)
            ->setParameter('season', $input->getArgument('season'))
            ->getResult();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $progress = $this->getHelperSet()->get('progress');
        $progress->start($output, count($this->portfolios));
        foreach ($this->portfolios as $portfolio) {
            $this->scorer->scorePortfolio($portfolio);
            $progress->advance();
        }
        $this->em->flush();
        $progress->finish();
    }
}
