<?php

namespace SofaChamps\Bundle\MarchMadnessBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket;

/**
 * @Route("/{season}")
 */
class MainController extends BaseController
{
    /**
     * @Route("/", name="mm_home")
     * @ParamConverter("bracket", class="SofaChampsMarchMadnessBundle:Bracket", options={"id" = "season"})
     * @Template
     */
    public function indexAction(Bracket $bracket, $season)
    {
        return array(
            'bracket' => $bracket,
            'season' => $season,
        );
    }
}
