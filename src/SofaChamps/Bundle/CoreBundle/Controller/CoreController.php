<?php

namespace SofaChamps\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * BaseController
 * Provides utility functions for all controllers
 *
 * @author Jonathon Suggs <jsuggs@gmail.com>
 */
class CoreController extends Controller
{
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
}
