<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\BowlPickemBundle\Form\TeamFormType;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/team")
 */
class TeamController extends Controller
{
    /**
     * @Route("/list", name="team_list")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("SofaChampsBowlPickemBundle:Admin:teams.html.twig")
     */
    public function listAction()
    {
        $teams = $this->getRepository('SofaChampsBowlPickemBundle:Team')
            ->findAll();

        return array(
            'teams' => $teams,
        );
    }

    /**
     * @Route("/new", name="team_new")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("SofaChampsBowlPickemBundle:Team:new.html.twig")
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
     * @Route("/create", name="team_create")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("SofaChampsBowlPickemBundle:Team:new.html.twig")
     */
    public function createAction()
    {
        $team = new Team();
        $form = $this->getTeamForm($team);
        $form->bindRequest($this->getRequest());

        if ($form->isValid()) {
            $team = $form->getData();
            $em = $this->getEntityManager();
            $em->persist($team);
            $em->flush();
        } else {
            return array(
                'form' => $form->createView(),
            );
        }

        return $this->redirect($this->generateUrl('team_list'));
    }

    /**
     * @Route("/{teamId}/edit", name="team_edit")
     * @Secure(roles="ROLE_ADMIN")
     * @ParamConverter("team", class="SofaChampsBowlPickemBundle:Team", options={"id" = "teamId"})
     * @Template
     */
    public function editAction(Team $team)
    {
        $form = $this->getTeamForm($team);

        return array(
            'form' => $form->createView(),
            'team' => $team,
        );
    }

    /**
     * @Route("/{teamId}/update", name="team_update")
     * @Secure(roles="ROLE_ADMIN")
     * @ParamConverter("team", class="SofaChampsBowlPickemBundle:Team", options={"id" = "teamId"})
     * @Template("SofaChampsBowlPickemBundle:Team:edit.html.twig")
     */
    public function updateAction(Team $team)
    {
        $form = $this->getTeamForm($team);
        $form->bindRequest($this->getRequest());

        if ($form->isValid()) {
            $team = $form->getData();
            $em = $this->getEntityManager();
            $em->persist($team);
            $em->flush();

            return $this->redirect($this->generateUrl('team_list'));
        }

        return array(
            'form' => $form->createView(),
            'team' => $team,
        );
    }

    private function getTeamForm(Team $team)
    {
        return $this->createForm(new TeamFormType(), $team);
    }
}
