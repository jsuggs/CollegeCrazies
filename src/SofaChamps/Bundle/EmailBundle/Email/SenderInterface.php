<?php

namespace SofaChamps\Bundle\EmailBundle\Email;

use SofaChamps\Bundle\CoreBundle\Entity\User;

interface SenderInterface
{
    function sendToUser(User $user, $templateName, $subjectLine, array $params);
}
