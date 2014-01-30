<?php

namespace SofaChamps\Bundle\EmailBundle\Email;

use JMS\DiExtraBundle\Annotation as DI;
use Mmoreram\GearmanBundle\Driver\Gearman;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * This is the process that actually sends the emails
 *
 * @DI\Service("sofachamps.email.worker")
 */
class EmailWorker
{
    protected $mailer;
    protected $templating;

    /**
     * @DI\InjectParams({
     *      "mailer" = @DI\Inject("mailer"),
     *      "templating" = @DI\Inject("templating"),
     * })
     */
    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function sendToEmail(\GearmanJob $job)
    {
        $workload = json_decode($job->workload(), true);

        var_dump(sprintf('Sending %s to %s with %s', $workload['templateName'], $workload['email'], var_export($workload['data'], true)));

        // Make the email being sent to available to the templates
        $workload['emailTo'] = $workload['email'];

        $html = $this->templating->render(sprintf('SofaChampsEmailBundle:%s.html.twig', $workload['templateName']), $workload['data']);
        $text = $this->templating->render(sprintf('SofaChampsEmailBundle:%s.text.twig', $workload['templateName']), $workload['data']);

        $from = array_key_exists('from', $workload['data'])
            ? $workload['data']['from']
            : array('help@sofachamps.com' => 'SofaChamps');

        $message = \Swift_Message::newInstance()
            ->setSubject($workload['subjectLine'])
            ->setFrom($from)
            ->setTo($workload['email'])
            ->setBody($html, 'text/html')
            ->addPart($text, 'text/plain');

        $this->mailer->send($message);

        return true;
    }
}
