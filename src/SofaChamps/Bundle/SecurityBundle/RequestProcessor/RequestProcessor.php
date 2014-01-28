<?php

namespace SofaChamps\Bundle\SecurityBundle\RequestProcessor;

use Symfony\Component\HttpFoundation\Request;

interface RequestProcessor
{
    /**
     * @param Request $request
     *
     * @return Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function processRequest(Request $request);
}
