<?php

namespace SofaChamps\Bundle\EmailBundle\Tests\Email;

use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\CoreBundle\Tests\SofaChampsTest;
use SofaChamps\Bundle\EmailBundle\Email\EmailSender;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class EmailSenderTest extends SofaChampsTest
{
    protected $gearman;
    protected $logger;
    protected $sender;

    protected function setUp()
    {
        parent::setUp();

        $this->gearman = $this->buildMock('Mmoreram\GearmanBundle\Service\GearmanClient');
        $this->logger = $this->buildMock('Symfony\Component\HttpKernel\Log\LoggerInterface');

        $this->sender = new EmailSender($this->gearman, $this->logger);
    }

    public function testSendToEmail()
    {
        $email = $this->getFaker()->email();
        $templateName = $this->getFaker()->word();
        $subjectLine = $this->getFaker()->word(3);

        $from = array(
            $this->getFaker()->email() => $this->getFaker()->name(),
        );

        $data = array(
            'form' => $from,
        );

        $payload = array(
            'email' => $email,
            'templateName' => $templateName,
            'subjectLine' => $subjectLine,
            'data' => $data,
        );

        $this->gearman->expects($this->once())
            ->method('doBackgroundJob')
            ->with('SofaChampsBundleEmailBundleEmailEmailSender~sendEmail', json_encode($payload));

        $this->sender->sendToEmail($email, $templateName, $subjectLine, $data);
    }

    public function testSendToEmailWithInvalidEmail()
    {
        $this->gearman->expects($this->never())
            ->method('doBackgroundJob');

        $this->sender->sendToEmail('invalidEmail', '', '');
    }
}
