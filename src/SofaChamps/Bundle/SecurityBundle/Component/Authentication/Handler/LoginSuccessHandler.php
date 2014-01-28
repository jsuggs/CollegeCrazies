<?php

namespace SofaChamps\Bundle\SecurityBundle\Component\Authentication\Handler;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\SecurityBundle\RequestProcessor\RequestProcessor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

/**
 * LoginSuccessHandler
 *
 * @DI\Service("sofachamps.component.authentication.handler.login_success_handler")
 * @DI\Tag("monolog.logger", attributes={"channel":"secutiry"})
 */
class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    protected $processors;

    public function addRequestProcessor(RequestProcessor $processor)
    {
        $this->processors[] = $processor;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        foreach ($this->processors as $processor) {
            if ($response = $processor->processRequest($request, $token->getUser())) {
                return $response;
            }
        }
    }
}
