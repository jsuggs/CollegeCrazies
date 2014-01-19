<?php

namespace SofaChamps\Bundle\EmailBundle\Email;

use SofaChamps\Bundle\CoreBundle\Entity\User;

interface EmailSenderInterface
{
    function sendToUser(User $user, $templateName, $subjectLine, array $params);
    function sendToEmail($email, $templateName, $subjectLine, array $data = array());
}
