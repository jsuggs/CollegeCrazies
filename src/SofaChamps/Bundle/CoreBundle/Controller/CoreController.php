<?php

namespace SofaChamps\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * BaseController
 * Provides utility functions for all controllers
 *
 * @author Jonathon Suggs <jsuggs@gmail.com>
 */
class CoreController extends Controller
{
    protected function getResponse()
    {
        return $this->has('response')
            ? $this->get('response')
            : new Response();
    }

    protected function setCookie(Response $response, $name, $value)
    {
        $response->headers->setCookie(new Cookie($name, $value));
    }

    /**
     * getEntityManager
     *
     * @return Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->get('doctrine.orm.default_entity_manager');
    }

    /**
     * getRepository
     *
     * @param string $entityName The entity name (ex. SofaChampsCoreBundle:User)
     *
     * @return Doctrine\ORM\EntityRepository
     */
    protected function getRepository($entityName)
    {
        return $this->getEntityManager()->getRepository($entityName);
    }

    protected function getSecurityContext()
    {
        return $this->get('security.context');
    }

    protected function getSession()
    {
        return $this->get('session');
    }

    protected function getFlashBag()
    {
        return $this->getSession()->getFlashBag();
    }

    protected function addMessage($type, $message)
    {
        $this->getFlashBag()->add($type, $message);
    }

    protected function getEventDispatcher()
    {
        return $this->get('event_dispatcher');
    }

    protected function getEmailSender()
    {
        return $this->get('sofachamps.email.sender');
    }

    protected function getEmailInputParser()
    {
        return $this->get('sofachamps.email.input_parser');
    }
}
