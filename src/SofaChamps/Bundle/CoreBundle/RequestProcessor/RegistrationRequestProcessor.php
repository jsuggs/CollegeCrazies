<?php

namespace SofaChamps\Bundle\CoreBundle\RequestProcessor;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\HttpFoundation\Request;

interface RegistrationRequestProcessor
{
    /**
     * @param Request $request
     *
     * @return Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function processRegisrationRequest(Request $request);

    /**
     * @param FilterUserResponseEvent $event
     *
     * @return null
     */
    public function handleRegistrationCompleted(FilterUserResponseEvent $event);
}
