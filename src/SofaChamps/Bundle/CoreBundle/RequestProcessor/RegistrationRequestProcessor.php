<?php

namespace SofaChamps\Bundle\CoreBundle\RequestProcessor;

use Symfony\Component\HttpFoundation\Request;

interface RegistrationRequestProcessor
{
    /**
     * @param Request $request
     *
     * @return Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function processRegisrationRequest(Request $request);
}
