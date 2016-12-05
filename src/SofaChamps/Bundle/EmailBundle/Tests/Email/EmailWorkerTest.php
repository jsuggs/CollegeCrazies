<?php

namespace SofaChamps\Bundle\EmailBundle\Tests\Email;

use SofaChamps\Bundle\CoreBundle\Tests\SofaChampsTest;
use SofaChamps\Bundle\EmailBundle\Email\EmailWorker;
use Swift_Mailer;
use GearmanJob;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Routing\RequestContext;

class EmailWorkerTest extends SofaChampsTest
{
    protected $mailer;
    protected $templating;
    protected $router;
    protected $logger;
    protected $worker;

    protected function setUp()
    {
        parent::setUp();

        $this->mailer = $this->buildMock(Swift_Mailer::class);
        $this->templating = $this->buildMock(EngineInterface::class);
        $this->router = $this->buildMock(UrlGeneratorInterface::class);
        $this->logger = $this->buildMock(LoggerInterface::class);
        $context = $this->buildMock(RequestContext::class);

        $this->router->expects($this->any())
            ->method('getContext')
            ->will($this->returnValue($context));

        $this->worker = new EmailWorker($this->mailer, $this->templating, $this->router, $this->logger);
    }

    public function testSendToEmail()
    {
        $this->markTestIncomplete('TODO');
        $email = $this->getFaker()->email();
        $templateName = $this->getFaker()->word();
        $subjectLine = $this->getFaker()->word(3);

        $from = array(
            $this->getFaker()->email() => $this->getFaker()->name(),
        );

        $html = $this->getFaker()->paragraph();
        $text = $this->getFaker()->paragraph();

        $data = array(
            'form' => $from,
        );

        $payload = array(
            'email' => $email,
            'templateName' => $templateName,
            'subjectLine' => $subjectLine,
            'data' => $data,
        );

        $job = $this->buildMock(GearmanJob::class);
        $job->expects($this->any())
            ->method('workload')
            ->will($this->returnValue(json_encode($payload)));

        $this->templating->expects($this->at(0))
            ->method('render')
            ->will($this->returnValue($html));

        $this->templating->expects($this->at(1))
            ->method('render')
            ->will($this->returnValue($text));

        $this->mailer->expects($this->once())
            ->method('send');

        $this->worker->sendToEmail($job);
    }
}
