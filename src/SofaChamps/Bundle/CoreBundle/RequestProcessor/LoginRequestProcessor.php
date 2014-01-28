<?php

namespace SofaChamps\Bundle\CoreBundle\RequestProcessor;

use SofaChamps\Bundle\CoreBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

interface LoginRequestProcessor
{
    /**
     * @param Request $request
     *
     * @return Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function processLoginRequest(Request $request, User $user);
}
