<?php

namespace SofaChamps\Bundle\EmailBundle\Email;

use SofaChamps\Bundle\BowlPickemBundle\Entity\User;

interface SenderInterface
{
    function sendToUser(User $user, $templateName, $subjectLine, array $params);
}
