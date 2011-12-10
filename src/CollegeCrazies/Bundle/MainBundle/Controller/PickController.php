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
     * @Route("/pick/list", name="pick_list")
     * @Template("CollegeCraziesMainBundle:Pick:list.html.twig")
     */
    public function listAction()
    {
        $user = new User();
        $pickSet = new PickSet();
        $pickSet->setUser($user);

        $em = $this->get('doctrine.orm.entity_manager');
        $games = $em->getRepository('CollegeCrazies\Bundle\MainBundle\Entity\Game')->findAll();
        foreach ($games as $game) {
            $pick = new Pick();
            $pick->setUser($user);
            $pick->setGame($game);
            $pick->setConfidence(4);

            $pickSet->addPick($pick);
        }

        $form = $this->getPickSetForm($pickSet);

        return array(
            'form' => $form->createView()
        );
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

    /**
     * @Route("/pick/create", name="pick_create")
     * @Template("CollegeCraziesMainBundle:Team:new.html.twig")
     */
    public function createAction()
    {
        $team = new Team();
        $form = $this->getTeamForm($team);
        $form->bindRequest($this->getRequest());

        if ($form->isValid()) {
            $team = $form->getData();
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($team);
            $em->flush();
        } else {
            return array('form' => $form);
        }

        return $this->redirect($this->generateUrl('team_list'));
    }

    private function getPickForm(Pick $pick)
    {
        return $this->createForm(new PickFormType(), $pick);
    }

    private function getPickSetForm(PickSet $pickSet)
    {
        return $this->createForm(new PickSetFormType(), $pickSet);
    }

    private function findTeam($id)
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
