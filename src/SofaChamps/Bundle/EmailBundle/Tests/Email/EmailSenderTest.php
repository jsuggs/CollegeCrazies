<?php

namespace SofaChamps\Bundle\EmailBundle\Tests\Email;

use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\CoreBundle\Tests\SofaChampsTest;
use SofaChamps\Bundle\EmailBundle\Email\EmailSender;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class EmailSenderTest extends SofaChampsTest
{
    protected $mailer;
    protected $templating;
    protected $logger;
    protected $emailSender;

    protected function setUp()
    {
        parent::setUp();

        $this->mailer = $this->buildMock('\Swift_Mailer');
        $this->templating = $this->buildMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $this->logger = $this->buildMock('Symfony\Component\HttpKernel\Log\LoggerInterface');

        $this->sender = new EmailSender($this->mailer, $this->templating, $this->logger);
    }

    public function testSendToEmail()
    {
        $email = $this->getFaker()->email();
        $template = $this->getFaker()->word();
        $subjectLine = $this->getFaker()->word(3);

        $from = array(
            $this->getFaker()->email() => $this->getFaker()->name(),
        );

        $html = $this->getFaker()->paragraph();
        $text = $this->getFaker()->paragraph();

        $data = array(
            'form' => $from,
        );

        $this->templating->expects($this->at(0))
            ->method('render')
            ->will($this->returnValue($html));

        $this->templating->expects($this->at(1))
            ->method('render')
            ->will($this->returnValue($text));

        $message = \Swift_Message::newInstance()
            ->setSubject($subjectLine)
            ->setFrom($from)
            ->setTo($email)
            ->setBody($html, 'text/html')
            ->addPart($text, 'text/plain');

        $this->mailer->expects($this->once())
            ->method('send');
            //->with($message);

        $this->sender->sendToEmail($email, $template, $subjectLine, $data);
    }

    public function testSendToEmailWithInvalidEmail()
    {
        $this->mailer->expects($this->never())
            ->method('send');

        $this->sender->sendToEmail('invalidEmail', '', '');
    }
}
