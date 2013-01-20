<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Controller;

use SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Config;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Pick;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Result;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Form\ConfigFormType;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Form\PickFormType;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Form\ResultFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BaseController extends Controller
{
    protected function getPickForm(Pick $pick = null)
    {
        return $this->createForm(new PickFormType(), $pick);
    }

    protected function getConfigForm(Config $config)
    {
        return $this->createForm(new ConfigFormType(), $config);
    }

    protected function getResultForm(Result $result)
    {
        return $this->createForm(new ResultFormType(), $result);
    }

    protected function findPick($pickId)
    {
        $pick = $this
            ->get('doctrine.orm.entity_manager')
            ->getRepository('SofaChampsSuperBowlChallengeBundle:Pick')
            ->find((int) $pickId);

        if (!$pick) {
            throw new NotFoundHttpException(sprintf('There was no pick with id = %s', $pickId));
        }

        return $pick;
    }

    protected function getPicksOrderedByScore()
    {
        return $this
            ->get('doctrine.orm.entity_manager')
            ->getRepository('SofaChampsSuperBowlChallengeBundle:Pick')
            ->findAll();
    }

    protected function getConfig($year = null)
    {
        $year = $year ?: date('Y');

        $config = $this
            ->get('doctrine.orm.entity_manager')
            ->getRepository('SofaChampsSuperBowlChallengeBundle:Config')
            ->find($year);

        return $config ?: new Config($year);
    }

    protected function getResult($year = null)
    {
        $year = $year ?: date('Y');

        $result = $this
            ->get('doctrine.orm.entity_manager')
            ->getRepository('SofaChampsSuperBowlChallengeBundle:Result')
            ->find($year);

        return $result ?: new Result($year);
    }
}
