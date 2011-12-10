<?php

namespace CollegeCrazies\Bundle\MainBundle\Controller;

use CollegeCrazies\Bundle\MainBundle\Form\TeamFormType;
use CollegeCrazies\Bundle\MainBundle\Entity\Team;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TeamController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/team/list", name="team_list")
     * @Template("CollegeCraziesMainBundle:Team:list.html.twig")
     */
    public function listAction()
    {
        return array();
        $em = $this->get('doctrine.orm.entity_manager');
        $teams = $em->getRepository('Team')->findAll();
        return array('teams' => $teams);
    }

    /**
     * @Route("/team/new", name="team_new")
     * @Template("CollegeCraziesMainBundle:Team:new.html.twig")
     */
    public function newAction()
    {
        $team = new Team();
        $form = $this->getTeamForm($team);

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/team/create", name="team_create")
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

    private function getTeamForm(Team $team)
    {
        return $this->createForm(new TeamFormType(), $team);
    }

    private function findTeam($id)
    {
        $team = $this->getRepository('Team')->find($id);
        if (!$team) {
            throw new \NotFoundHttpException(sprintf('There was no team with id = %s', $id));
        }
        return $team;
    }
}
