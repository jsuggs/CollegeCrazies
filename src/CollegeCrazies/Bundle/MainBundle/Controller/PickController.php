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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PickController extends Controller
{
    /**
     * @Route("/pick-list/new", name="picklist_new")
     * @Template("CollegeCraziesMainBundle:Pick:new.html.twig")
     */
    public function newPickAction()
    {
        $pickSet = new PickSet();

        $user = $this->get('security.context')->getToken()->getUser();

        if ($user == 'anon.') {
            throw new AccessDeniedException();
        }
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
     * @Route("/pick-list/edit/{id}", name="pickset_edit")
     * @Template("CollegeCraziesMainBundle:Pick:edit.html.twig")
     */
    public function editPickAction($id) 
    {
        $pickSet = $this->findPickSet($id);

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
            die('here');
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($pickSet);
            $em->flush();
            return $this->redirect($this->generateUrl('pickset_edit', array(
                'id' => $pickSet->getId()
            )));
        } else {
            //die(var_dump($form));
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

    private function findPickSet($id)
    {
        $team = $this->getRepository('CollegeCraziesMainBundle\Bundle\MainBundle\Entity\PickSet')->find($id);
        if (!$team) {
            throw new \NotFoundHttpException(sprintf('There was no team with id = %s', $id));
        }
        return $team;
    }
}
