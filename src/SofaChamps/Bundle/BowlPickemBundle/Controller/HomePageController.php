<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Controller;

use SofaChamps\Bundle\BowlPickemBundle\Form\UserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class HomePageController extends Controller
{
    /**
     * @Route("/")
     */
    public function homepageAction()
    {
        $user = $this->getUser();

        $template = $this->get('security.context')->isGranted('ROLE_USER')
            ? 'SofaChampsBowlPickemBundle::homepage.auth.html.twig'
            : 'SofaChampsBowlPickemBundle::homepage.unauth.html.twig';

        $form = $this->createForm(new UserFormType());
        return $this->render($template , array(
            'form' => $form->createView(),
            'user' => $user
        ));
    }

    /**
     * @Route("/how-to", name="howto")
     * @Template("SofaChampsBowlPickemBundle::howto.html.twig")
     */
    public function howtoAction()
    {
        return array();
    }

    /**
     * @Route("/about", name="about")
     * @Template("SofaChampsBowlPickemBundle::about.html.twig")
     */
    public function aboutAction()
    {
        return array();
    }

    /**
     * @Route("/donate", name="donate")
     * @Template("SofaChampsBowlPickemBundle::donate.html.twig")
     */
    public function donateAction()
    {
        return array();
    }

    /**
     * @Route("/donate-thanks", name="donate_thanks")
     * @Template("SofaChampsBowlPickemBundle::donate-thanks.html.twig")
     */
    public function donateThanksAction()
    {
        return array();
    }

    /**
     * @Route("/bowl-schedule", name="schedule")
     * @Template("SofaChampsBowlPickemBundle::schedule.html.twig")
     */
    public function scheduleAction()
    {
        return array(
            'games' => array_reverse($this->get('doctrine.orm.entity_manager')->getRepository('SofaChampsBowlPickemBundle:Game')->findAllOrderedByDate()),
        );
    }

    /**
     * @Route("/prediction-info", name="prediction_info")
     * @Template("SofaChampsBowlPickemBundle::prediction-info.html.twig")
     */
    public function predictionInfoAction()
    {
        return array();
    }
}
