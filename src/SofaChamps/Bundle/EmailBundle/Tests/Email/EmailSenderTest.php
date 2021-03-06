<?php

namespace SofaChamps\Bundle\EmailBundle\Tests\Email;

use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\CoreBundle\Tests\SofaChampsTest;
use SofaChamps\Bundle\EmailBundle\Email\EmailSender;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class EmailSenderTest extends SofaChampsTest
{
    protected $gearman;
    protected $serializer;
    protected $logger;
    protected $sender;

    protected function setUp()
    {
        parent::setUp();

        $this->gearman = $this->buildMock('Mmoreram\GearmanBundle\Service\GearmanClient');
        $this->serializer = $this->buildMock('JMS\Serializer\Serializer');
        $this->logger = $this->buildMock('Symfony\Component\HttpKernel\Log\LoggerInterface');

        $this->sender = new EmailSender($this->gearman, $this->serializer, $this->logger);
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
        $serializedData = uniqid();

        $this->serializer->expects($this->once())
            ->method('serialize')
            ->with($payload, 'json')
            ->will($this->returnValue($serializedData));

        $this->gearman->expects($this->once())
            ->method('doBackgroundJob')
            ->with('SofaChampsBundleEmailBundleEmailEmailSender~sendEmail', $serializedData);

        $this->sender->sendToEmail($email, $templateName, $subjectLine, $data);
    }

    public function testSendToEmailWithInvalidEmail()
    {
        $this->gearman->expects($this->never())
            ->method('doBackgroundJob');

        $this->sender->sendToEmail('invalidEmail', '', '');
    }
}
