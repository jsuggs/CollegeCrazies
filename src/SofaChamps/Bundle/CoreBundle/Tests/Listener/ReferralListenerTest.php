<?php

namespace SofaChamps\Bundle\CoreBundle\Tests\Referral;

use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\CoreBundle\Listener\ReferralListener;
use SofaChamps\Bundle\CoreBundle\Tests\SofaChampsTest;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ReferralListenerTest extends SofaChampsTest
{
    protected $securityContext;
    protected $em;
    protected $session;
    protected $referralManager;
    protected $listener;

    protected function setUp()
    {
        $this->securityContext = $this->buildMock('Symfony\Component\Security\Core\SecurityContext');
        $this->em = $this->buildMock('Doctrine\ORM\EntityManager');
        $this->session = $this->buildMock('Symfony\Component\HttpFoundation\Session\Session');
        $this->referralManager = $this->buildMock('SofaChamps\Bundle\CoreBundle\Referral\ReferralManager');

        $this->listener = new ReferralListener($this->securityContext, $this->em, $this->session, $this->referralManager);
    }

    public function testOnKernelRequest()
    {
        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $this->securityContext->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue($token));

        $token->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue(null));

        $referralId = uniqid();
        $query = $this->buildMock('Symfony\Component\HttpFoundation\ParameterBag');
        $query->expects($this->any())
            ->method('get')
            ->with(ReferralListener::REFERRAL_PARAM)
            ->will($this->returnValue($referralId));

        $request = $this->buildMock('Symfony\Component\HttpFoundation\Request');
        $request->query = $query;

        $event = $this->buildMock('Symfony\Component\HttpKernel\Event\GetResponseEvent');
        $event->expects($this->any())
            ->method('getRequestType')
            ->will($this->returnValue(HttpKernelInterface::MASTER_REQUEST));
        $event->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request));

        $this->session->expects($this->once())
            ->method('set')
            ->with(ReferralListener::REFERRAL_SESSION, $referralId);

        $this->listener->onKernelRequest($event);
    }

    public function testOnKernelRequestNoReferrParam()
    {
        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $this->securityContext->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue($token));

        $token->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue(null));

        $query = $this->buildMock('Symfony\Component\HttpFoundation\ParameterBag');
        $query->expects($this->any())
            ->method('get')
            ->with(ReferralListener::REFERRAL_PARAM)
            ->will($this->returnValue(null));

        $request = $this->buildMock('Symfony\Component\HttpFoundation\Request');
        $request->query = $query;

        $event = $this->buildMock('Symfony\Component\HttpKernel\Event\GetResponseEvent');
        $event->expects($this->any())
            ->method('getRequestType')
            ->will($this->returnValue(HttpKernelInterface::MASTER_REQUEST));
        $event->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request));

        $this->session->expects($this->never())
            ->method('set');

        $this->listener->onKernelRequest($event);
    }

    public function testOnKernelRequestLoggedInUser()
    {
        $event = $this->buildMock('Symfony\Component\HttpKernel\Event\GetResponseEvent');
        $event->expects($this->any())
            ->method('getRequestType')
            ->will($this->returnValue(HttpKernelInterface::MASTER_REQUEST));

        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $this->securityContext->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue($token));

        $token->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue(new User()));

        $this->session->expects($this->never())
            ->method('set');

        $this->listener->onKernelRequest($event);
    }

    public function testOnKernelRequestSubRequest()
    {
        $event = $this->buildMock('Symfony\Component\HttpKernel\Event\GetResponseEvent');
        $event->expects($this->any())
            ->method('getRequestType')
            ->will($this->returnValue(HttpKernelInterface::SUB_REQUEST));

        $this->session->expects($this->never())
            ->method('set');

        $this->listener->onKernelRequest($event);
    }

    public function testOnKernelResponse()
    {
        $referralId = uniqid();
        $this->session->expects($this->any())
            ->method('get')
            ->with(ReferralListener::REFERRAL_SESSION)
            ->will($this->returnValue($referralId));

        $cookies = $this->buildMock('Symfony\Component\HttpFoundation\ParameterBag');
        $cookies->expects($this->any())
            ->method('has')
            ->with(ReferralListener::REFERRAL_COOKIE)
            ->will($this->returnValue(false));

        $headers = $this->buildMock('Symfony\Component\HttpFoundation\ResponseHeaderBag');
        $headers->expects($this->once())
            ->method('setCookie');

        $request = $this->buildMock('Symfony\Component\HttpFoundation\Request');
        $request->cookies = $cookies;

        $response = $this->buildMock('Symfony\Component\HttpFoundation\Response');
        $response->headers = $headers;

        $event = $this->buildMock('Symfony\Component\HttpKernel\Event\FilterResponseEvent');
        $event->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request));
        $event->expects($this->any())
            ->method('getResponse')
            ->will($this->returnValue($response));

        $this->listener->onKernelResponse($event);
    }

    public function testAddReferrerToUser()
    {
        $referralId = uniqid();
        $user = new User();
        $referrer = new User();

        $cookies = $this->buildMock('Symfony\Component\HttpFoundation\ParameterBag');
        $cookies->expects($this->any())
            ->method('get')
            ->with(ReferralListener::REFERRAL_COOKIE)
            ->will($this->returnValue($referralId));

        $request = $this->buildMock('Symfony\Component\HttpFoundation\Request');
        $request->cookies = $cookies;

        $repo = $this->buildMock('SofaChamps\Bundle\CoreBundle\Entity\UserRepository');
        $repo->expects($this->once())
            ->method('find')
            ->with($referralId)
            ->will($this->returnValue($referrer));

        $this->em->expects($this->any())
            ->method('getRepository')
            ->with('SofaChampsCoreBundle:User')
            ->will($this->returnValue($repo));

        $event = $this->buildMock('FOS\UserBundle\Event\FilterUserResponseEvent');
        $event->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request));
        $event->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue($user));

        $this->referralManager->expects($this->once())
            ->method('addReferrerToUser')
            ->with($user, $referrer);

        $this->listener->addReferrerToUser($event);
    }
}
