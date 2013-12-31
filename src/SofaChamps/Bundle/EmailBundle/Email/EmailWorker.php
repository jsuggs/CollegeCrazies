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
        $data = json_decode($job->workload(), true);
        var_dump($data);

        // Make the email being sent to available to the templates
        $data['emailTo'] = $data['email'];

        $html = '';//$this->templating->render(sprintf('SofaChampsEmailBundle:%s.html.twig', $data['templateName']), $data);
        $text = '';//$this->templating->render(sprintf('SofaChampsEmailBundle:%s.text.twig', $data['templateName']), $data);

        $from = array_key_exists('from', $data)
            ? $data['from']
            : array('help@sofachamps.com' => 'SofaChamps');

        $message = \Swift_Message::newInstance()
            ->setSubject($data['subjectLine'])
            ->setFrom($from)
            ->setTo($data['email'])
            ->setBody($html, 'text/html')
            ->addPart($text, 'text/plain');
        var_dump($message);

        return true;
    }
}
