<?php

namespace SofaChamps\Bundle\EmailBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendEmailCommand extends ContainerAwareCommand
{
    protected $sender;

    protected function configure()
    {
        $this->setName('email:send-email')
            ->setDescription('Send an email')
            ->addArgument('email', InputArgument::REQUIRED, 'email')
            ->addArgument('template', InputArgument::REQUIRED, 'template')
            ->addArgument('subject', InputArgument::REQUIRED, 'subject')
            ->addArgument('data', InputArgument::OPTIONAL, 'json data', '[]')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->sender = $this->getContainer()->get('sofachamps.email.sender');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->sender->sendToEmail(
            $input->getArgument('email'),
            $input->getArgument('template'),
            $input->getArgument('subject'),
            json_decode($input->getArgument('data'), true)
        );
    }
}
