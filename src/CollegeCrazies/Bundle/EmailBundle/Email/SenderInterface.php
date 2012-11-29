<?php

namespace CollegeCrazies\Bundle\EmailBundle\Email;

use CollegeCrazies\Bundle\MainBundle\Entity\User;

interface SenderInterface
{
    function sendToUser(User $user, $templateName, $subjectLine, array $params);
}
