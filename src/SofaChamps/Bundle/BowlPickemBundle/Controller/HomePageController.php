<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Season;
use SofaChamps\Bundle\BowlPickemBundle\Form\UserFormType;

class HomePageController extends BaseController
{
    /**
     * @Route("/{season}", requirements={"season" = "\d+"},  name="bp_home")
     */
    public function homepageAction(Season $season)
    {
        $user = $this->getUser();

        $template = $this->get('security.context')->isGranted('ROLE_USER')
            ? 'SofaChampsBowlPickemBundle::homepage.auth.html.twig'
            : 'SofaChampsBowlPickemBundle::homepage.unauth.html.twig';

        $form = $this->createForm(new UserFormType());
        return $this->render($template , array(
            'form' => $form->createView(),
            'season' => $season,
            'user' => $user,
        ));
    }

    /**
     * @Route("/how-to", name="howto")
     * @Template("SofaChampsBowlPickemBundle::howto.html.twig")
     */
    public function howtoAction()
    {
        return array(
            'season' => $this->getCurrentSeason(),
        );
    }

    /**
     * @Route("/about", name="about")
     * @Template("SofaChampsBowlPickemBundle::about.html.twig")
     */
    public function aboutAction()
    {
        return array(
            'season' => $this->getCurrentSeason(),
        );
    }

    /**
     * @Route("/donate-thanks", name="donate_thanks")
     * @Template("SofaChampsBowlPickemBundle::donate-thanks.html.twig")
     */
    public function donateThanksAction()
    {
        return array(
            'season' => $this->getCurrentSeason(),
        );
    }

    /**
     * @Route("/{season}/bowl-schedule", requirements={"season" = "\d+"}, name="schedule")
     * @Template("SofaChampsBowlPickemBundle::schedule.html.twig")
     */
    public function scheduleAction(Season $season)
    {
        return array(
            'games' => $this->getRepository('SofaChampsBowlPickemBundle:Game')->findAllOrderedByDate($season, 'ASC'),
            'season' => $season,
        );
    }

    /**
     * @Route("/prediction-info", name="prediction_info")
     * @Template("SofaChampsBowlPickemBundle::prediction-info.html.twig")
     */
    public function predictionInfoAction()
    {
        return array(
            'season' => $this->getCurrentSeason(),
        );
    }
}
