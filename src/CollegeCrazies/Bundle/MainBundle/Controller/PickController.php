<?php

namespace CollegeCrazies\Bundle\MainBundle\Controller;

use CollegeCrazies\Bundle\MainBundle\Form\PickFormType;
use CollegeCrazies\Bundle\MainBundle\Form\PickSetFormType;
use CollegeCrazies\Bundle\MainBundle\Entity\Team;
use CollegeCrazies\Bundle\MainBundle\Entity\User;
use CollegeCrazies\Bundle\MainBundle\Entity\Pick;
use CollegeCrazies\Bundle\MainBundle\Entity\PickSet;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PickController extends Controller
{
    /**
     * @Route("/picks")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/pick-list/new", name="picklist_new")
     * @Template("CollegeCraziesMainBundle:Pick:new.html.twig")
     */
    public function newPickAction()
    {
        $pickSet = new PickSet();

        $user = $this->get('security.context')->getToken()->getUser();
        $pickSet->setUser($user);

        $em = $this->get('doctrine.orm.entity_manager');
        $games = $em->getRepository('CollegeCrazies\Bundle\MainBundle\Entity\Game')->findAll();
        $idx = count($games);
        foreach ($games as $game) {
            $pick = new Pick();
            $pick->setUser($user);
            $pick->setGame($game);
            $pick->setConfidence($idx);

            $pickSet->addPick($pick);
            $idx--;
        }

        $form = $this->getPickSetForm($pickSet);

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/pick-list/create", name="picklist_create")
     * @Template("CollegeCraziesMainBundle:Pick:new.html.twig")
     */
    public function createPickAction()
    {
        $pickSet = new PickSet();
        $form = $this->getPickSetForm($pickSet);
        $form->bindRequest($this->getRequest());

        if ($form->isValid()) {
            $pickSet = $form->getData();
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($pickSet);
            $em->flush();
            return $this->redirect($this->generateUrl('team_list'));
        } else {
            return array('form' => $form->createView());
        }
    }

    /**
     * @Route("/pick/new", name="pick_new")
     * @Template("CollegeCraziesMainBundle:Team:new.html.twig")
     */
    public function newAction()
    {
        $pick = new Pick();
        $form = $this->getPickForm($pick);

        return array(
            'form' => $form->createView()
        );
    }

    private function getPickForm(Pick $pick)
    {
        return $this->createForm(new PickFormType(), $pick);
    }

    private function getPickSetForm(PickSet $pickSet)
    {
        return $this->createForm(new PickSetFormType(), $pickSet);
    }

    private function findPickList($id)
    {
        $team = $this->getRepository('Team')->find($id);
        if (!$team) {
            throw new \NotFoundHttpException(sprintf('There was no team with id = %s', $id));
        }
        return $team;
    }

    public function userPick($user_id, $pick)
    {
    }
}
