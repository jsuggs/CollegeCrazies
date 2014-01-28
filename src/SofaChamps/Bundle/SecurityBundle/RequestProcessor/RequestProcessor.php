<?php

namespace SofaChamps\Bundle\SecurityBundle\RequestProcessor;

use SofaChamps\Bundle\CoreBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

interface RequestProcessor
{
    /**
     * @param Request $request
     *
     * @return Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function processRequest(Request $request, User $user);
}
