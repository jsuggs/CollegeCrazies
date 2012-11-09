<?php

namespace CollegeCrazies\Bundle\MainBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/prediction")
 */
class PredictionController extends Controller
{
    /**
     * @Route("/outcome/{pickSetId}/{predictionSetId}")
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function outcomeAction($pickSetId, $predictionSetId)
    {
        $pickSet = $this->findPickSet($pickSetId);
        $predictionSet = $this->findPredictionSet($predictionSetId);

        return array(
            'pickSet' => $pickSet,
            'predictionSet' => $predictionSet,
        );
    }

    private function findPickSet($pickSetId)
    {
        $pickSet = $this
            ->get('doctrine.orm.entity_manager')
            ->getRepository('CollegeCraziesMainBundle:PickSet')
            ->find($pickSetId);

        if (!$pickSet) {
            throw new NotFoundHttpException(sprintf('There was no pickSet with id = %s', $pickSetId));
        }

        return $pickSet;
    }

    private function findPredictionSet($predictionSetId)
    {
        $predictionSet = $this
            ->get('doctrine.orm.entity_manager')
            ->getRepository('CollegeCraziesMainBundle:PredictionSet')
            ->find($predictionSetId);

        if (!$predictionSet) {
            throw new NotFoundHttpException(sprintf('There was no prediction set with id = %s', $predictionSetId));
        }

        return $predictionSet;
    }
}
