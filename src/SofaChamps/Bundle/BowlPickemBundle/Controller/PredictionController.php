<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/prediction")
 */
class PredictionController extends BaseController
{
    /**
     * @Route("/outcome/{pickSetId}/{predictionSetId}", name="prediction_view")
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
}
