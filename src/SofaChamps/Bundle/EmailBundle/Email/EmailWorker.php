<?php

namespace SofaChamps\Bundle\EmailBundle\Email;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

use Mmoreram\GearmanBundle\Driver\Gearman;

/**
 * This sends all of the email
 *
 * @Gearman\Work(
 *      name = "EmailWoker",
 *      description = "Send Emails",
 *      defaultMethod = "doBackground",
 *      service = "sendEmailXX"
 * )
 */
class EmailWorker
{
    /**
     * @Gearman\Work(
     *      name = "sendEmail",
     * )
     */
    public function sendEmail(\GearmanJob $job)
    {
        echo 'sendEmail worked!' . PHP_EOL;

        return true;
    }
}
