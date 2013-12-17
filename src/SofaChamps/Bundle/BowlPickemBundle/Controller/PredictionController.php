<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\BowlPickemBundle\Entity\PickSet;
use SofaChamps\Bundle\BowlPickemBundle\Entity\PredictionSet;

/**
 * @Route("/{season}/prediction")
 */
class PredictionController extends BaseController
{
    /**
     * @Route("/outcome/{pickSetId}/{predictionSetId}", name="prediction_view")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("pickSet", class="SofaChampsBowlPickemBundle:PickSet", options={"id" = "pickSetId"})
     * @ParamConverter("predictionSet", class="SofaChampsBowlPickemBundle:PredictionSet", options={"id" = "predictionSetId"})
     * @Template
     */
    public function outcomeAction(PickSet $pickSet, PredictionSet $predictionSet, $season)
    {
        return array(
            'season' => $season,
            'pickSet' => $pickSet,
            'predictionSet' => $predictionSet,
        );
    }
}
