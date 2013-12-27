<?php

namespace SofaChamps\Bundle\MarchMadnessBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateBracketCommand extends ContainerAwareCommand
{
    protected $bracketManager;
    protected $em;

    protected function configure()
    {
        $this->setName('march-madness:create-bracket')
            ->setDescription('Creates an empty bracket for a season')
            ->addArgument('season', InputArgument::REQUIRED, 'The season to create the bracket for')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->bracketManager = $this->getContainer()->get('sofachamps.mm.bracket_manager');
        $this->em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bracket = $this->bracketManager->createBracket($input->getArgument('season'));
        $this->bracketManager->createBracketGames($bracket, 5);
        $this->em->flush();
    }
}
