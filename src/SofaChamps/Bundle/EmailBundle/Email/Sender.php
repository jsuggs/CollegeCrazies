<?php

namespace SofaChamps\Bundle\EmailBundle\Email;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

/**
 * Sender
 * This sends all of the email
 *
 * @DI\Service("sofachamps.email.sender")
 */
class Sender implements SenderInterface
{
    protected $mailer;
    protected $templating;
    protected $logger;

    /**
     * @DI\InjectParams({
     *      "mailer" = @DI\Inject("mailer"),
     *      "templating" = @DI\Inject("templating"),
     *      "logger" = @DI\Inject("logger")
     * })
     */
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
        // Make the email being sent to available to the templates
        $data['emailTo'] = $email;

        $html = $this->templating->render(sprintf('SofaChampsEmailBundle:%s.html.twig', $templateName), $data);
        $text = $this->templating->render(sprintf('SofaChampsEmailBundle:%s.text.twig', $templateName), $data);

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

