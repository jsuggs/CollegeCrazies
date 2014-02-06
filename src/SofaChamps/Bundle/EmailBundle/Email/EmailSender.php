<?php

namespace SofaChamps\Bundle\EmailBundle\Email;

use JMS\DiExtraBundle\Annotation as DI;
use JMS\Serializer\Serializer;
use Mmoreram\GearmanBundle\Driver\Gearman;
use Mmoreram\GearmanBundle\Service\GearmanClient;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

/**
 * This service will send email
 *
 * @Gearman\Work(
 *      name = "EmailSender",
 *      description = "Main email interface.  Will handle all proxying of email to the queue",
 *      defaultMethod = "doBackground",
 *      service = "sofachamps.email.worker",
 * )
 * @DI\Service("sofachamps.email.sender")
 */
class EmailSender implements EmailSenderInterface
{
    protected $gearman;
    protected $serializer;
    protected $logger;

    /**
     * @DI\InjectParams({
     *      "gearman" = @DI\Inject("gearman"),
     *      "serializer" = @DI\Inject("jms_serializer"),
     *      "logger" = @DI\Inject("logger"),
     * })
     */
    public function __construct(GearmanClient $gearman, Serializer $serializer, LoggerInterface $logger)
    {
        $this->gearman = $gearman;
        $this->serializer = $serializer;
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

    /**
     * @Gearman\Job(
     *      name = "sendEmail",
     *      description = "This will send an email",
     *      iterations = 10,
     * )
     */
    public function sendToEmail($email, $templateName, $subjectLine, array $data = array())
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $payload = $this->serializer->serialize(array(
            'email' => $email,
            'templateName' => $templateName,
            'subjectLine' => $subjectLine,
            'data' => $data,
        ), 'json');

        $this->gearman->doBackgroundJob('SofaChampsBundleEmailBundleEmailEmailSender~sendEmail', $payload);

        $this->logger->info(sprintf('Sent "%s" template "%s"', $email, $templateName));
    }
}

