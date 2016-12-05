<?php

namespace SofaChamps\Bundle\EmailBundle\Email;

use JMS\DiExtraBundle\Annotation as DI;
use GearmanJob;
use Mmoreram\GearmanBundle\Driver\Gearman;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Swift_Mailer;
use Swift_Message;

/**
 * This is the process that actually sends the emails
 *
 * @DI\Service("sofachamps.email.worker")
 * @DI\Tag("monolog.logger", attributes={"channel":"email"})
 */
class EmailWorker
{
    protected $mailer;
    protected $templating;
    protected $router;
    protected $logger;

    /**
     * @DI\InjectParams({
     *      "mailer" = @DI\Inject("mailer"),
     *      "templating" = @DI\Inject("templating"),
     *      "router" = @DI\Inject("router"),
     * })
     */
    public function __construct(Swift_Mailer $mailer, EngineInterface $templating, UrlGeneratorInterface $router, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->router = $router;
        $this->logger = $logger;
    }

    public function sendToEmail(GearmanJob $job)
    {
        $workload = json_decode($job->workload(), true);

        $this->logger->info(sprintf('Sending %s to %s with %s', $workload['templateName'], $workload['email'], json_encode($workload['data'], true)));

        // Make the email being sent to available to the templates
        $workload['emailTo'] = $workload['email'];

        // Set the context
        $context = $this->router->getContext();
        $context->setHost('www.sofachamps.com');
        $context->setScheme('http');

        $html = $this->templating->render(sprintf('SofaChampsEmailBundle:%s.html.twig', $workload['templateName']), $workload['data']);
        $text = $this->templating->render(sprintf('SofaChampsEmailBundle:%s.text.twig', $workload['templateName']), $workload['data']);

        $from = array_key_exists('from', $workload['data'])
            ? $workload['data']['from']
            : array('help@sofachamps.com' => 'SofaChamps');

        $message = Swift_Message::newInstance()
            ->setSubject($workload['subjectLine'])
            ->setFrom($from)
            ->setTo($workload['email'])
            ->setBody($html, 'text/html')
            ->addPart($text, 'text/plain');

        $this->mailer->send($message);

        return true;
    }
}
