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
     * @ParamConverter("pickSet", class="SofaChampsBowlPickemBundle:PickSet", options={"id" = "pickSetId"})
     * @ParamConverter("pickSet", class="SofaChampsBowlPickemBundle:PredictionSet", options={"id" = "predictionSetId"})
     * @Template
     */
    public function outcomeAction($pickSetId, $predictionSetId)
    {
        return array(
            'pickSet' => $pickSet,
            'predictionSet' => $predictionSet,
        );
    }
}
