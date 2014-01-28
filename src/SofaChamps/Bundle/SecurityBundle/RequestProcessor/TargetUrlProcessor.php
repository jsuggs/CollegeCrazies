<?php

namespace SofaChamps\Bundle\SecurityBundle\RequestProcessor;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * TargetUrlProcessor
 *
 * @DI\Service
 * @DI\Tag("sofachamps.request_processor")
 */
class TargetUrlProcessor implements RequestProcessor
{
    public function processRequest(Request $request)
    {
        return new RedirectResponse($this->getTargetUrl($request));
    }

    protected function getTargetUrl(Request $request)
    {
        if ($targetUrl = $request->get('_target_path', null, true)) {
            return $targetUrl;
        }

        // This is because the firewall is named "main"
        $sessionKey = '_security.main.target_path';
        if ($targetUrl = $request->getSession()->get($sessionKey)) {
            $request->getSession()->remove($sessionKey);

            return $targetUrl;
        }

        return '/';
    }
}
