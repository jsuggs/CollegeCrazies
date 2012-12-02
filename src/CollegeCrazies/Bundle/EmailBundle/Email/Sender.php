<?php

namespace CollegeCrazies\Bundle\EmailBundle\Email;

use CollegeCrazies\Bundle\MainBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

class Sender implements SenderInterface
{
    protected $mailer;
    protected $templating;
    protected $logger;

    public function __construct($mailer, EngineInterface $templating, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->logger = $logger;
    }

    public function sendToUser(User $user, $templateName, $subjectLine, array $data = array())
    {
        $this->sendToEmail($user->getEmailCanonical(), $templateName, $subjectLine, $data);
    }

    public function sendToUsers(array $users, $templateName, $subjectLine, array $data = array())
    {
        foreach ($users as $user) {
            $this->sendToUser($user, $templateName, $subjectLine, $data);
        }
    }

    public function sendToEmails(array $emails, $templateName, $subjectLine, array $data = array())
    {
        foreach ($emails as $email) {
            $this->sendToEmail($email, $templateName, $subjectLine, $data);
        }
    }

    public function sendToEmail($email, $templateName, $subjectLine, array $data = array())
    {
        $html = $this->templating->render(sprintf('CollegeCraziesEmailBundle:%s.html.twig', $templateName), $data);
        $text = $this->templating->render(sprintf('CollegeCraziesEmailBundle:%s.text.twig', $templateName), $data);

        $from = array_key_exists('from', $data)
            ? $data['from']
            : array('help@sofachamps.com' => 'SofaChamps');

        $message = \Swift_Message::newInstance()
            ->setSubject($subjectLine)
            ->setFrom($from)
            ->setTo($email)
            ->setBody($html, 'text/html')
            ->addPart($text, 'text/plain');

        $response = $this->mailer->send($message);

        $this->logger->info(sprintf('Sent "%s" template "%s"', $email, $templateName));
    }
}

