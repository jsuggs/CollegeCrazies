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
     * @Route("/my-picks", name="my_picks")
     */
    public function myPicksAction()
    {
        //
        $user = $this->get('security.context')->getToken()->getUser();

        if ($user == 'anon.') {
            throw new AccessDeniedException();
        }

        $pickSet = $user->getPickSet();
        if ($pickSet) {
            return $this->redirect($this->generateUrl('pickset_edit', array(
                'id' => $pickSet->getId()
            )));
        }

        return $this->redirect($this->generateUrl('picklist_new'));
        
    }

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
        $user = $this->get('security.context')->getToken()->getUser();

        if ($pickSet->getUser() !== $user) {
            $this->get('session')->setFlash('error','You cannot edit another users picks');
            return $this->redirect('/');
        }

        $form = $this->getPickSetForm($pickSet);
        return array(
            'form' => $form->createView(),
            'pickSet' => $pickSet
        );
    }

    /**
     * @Route("/pick-list/view/{id}", name="pickset_view")
     * @Template("CollegeCraziesMainBundle:Pick:view.html.twig")
     */
    public function viewPickAction($id) 
    {
        $pickSet = $this->findPickSet($id);
        $user = $this->get('security.context')->getToken()->getUser();

        if ($pickSet->getUser() !== $user) {
            $this->get('session')->setFlash('error','You cannot edit another users picks');
            return $this->redirect('/');
        }

        return array(
            'pickSet' => $pickSet
        );
    }

    /**
     * @Route("/pick-list/create", name="picklist_create")
     */
    public function createPickAction()
    {
        $pickSet = new PickSet();
        $form = $this->getPickSetForm($pickSet);
        $request = $this->getRequest();
        $form->bindRequest($request);

        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->get('doctrine.orm.entity_manager');
        $gameRepo = $em->getRepository('CollegeCrazies\Bundle\MainBundle\Entity\Game');
        $teamRepo = $em->getRepository('CollegeCrazies\Bundle\MainBundle\Entity\Team');

        $pickSet->setUser($user);
        $pickset = $request->request->get('pickset');

        foreach ($pickset['picks'] as $pickObj) {
            $pick = new Pick();
            $pick->setUser($user);
            $game = $gameRepo->find($pickObj['game']['id']);
            $pick->setGame($game);

            $team = $teamRepo->find($pickObj['team']['id']);
            if ($team) {
                $pick->setTeam($team);
            }

            $pick->setConfidence($pickObj['confidence']);
            $pick->setPickSet($pickSet);
            $em->persist($pick);

            $pickSet->addPick($pick);
        }
        $user->setPickSet($pickSet);
        $em->persist($user);
        $em->persist($pickSet);
        $em->flush();
        return $this->redirect($this->generateUrl('pickset_edit', array(
            'id' => $pickSet->getId()
        )));
    }

    /**
     * @Route("/pick-list/update/{id}", name="picklist_update")
     */
    public function updatePickAction($id)
    {
        $pickSet = $this->findPickSet($id);

        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->get('doctrine.orm.entity_manager');
        $gameRepo = $em->getRepository('CollegeCrazies\Bundle\MainBundle\Entity\Game');
        $teamRepo = $em->getRepository('CollegeCrazies\Bundle\MainBundle\Entity\Team');

        $pickSet->setUser($user);
        $request = $this->getRequest();
        $pickset = $request->request->get('pickset');
        $pickSet->setName($pickset['name']);
        $pickSet->setTiebreakerHomeTeamScore($pickset['tiebreakerHomeTeamScore']);
        $pickSet->setTiebreakerAwayTeamScore($pickset['tiebreakerAwayTeamScore']);

        // Delete and readd
        $picks = $pickSet->getPicks();
        foreach ($picks as $pick) {
            $em->remove($pick);
        }
        $pickSet->setPicks(null);
        $em->flush();

        foreach ($pickset['picks'] as $pickObj) {
            $pick = new Pick();
            $pick->setUser($user);
            $game = $gameRepo->find($pickObj['game']['id']);
            $pick->setGame($game);

            $team = $teamRepo->find($pickObj['team']['id']);
            if ($team) {
                $pick->setTeam($team);
            }

            $pick->setConfidence($pickObj['confidence']);
            $pick->setPickSet($pickSet);
            $em->persist($pick);

            $pickSet->addPick($pick);
        }
        $em->persist($pickSet);
        $em->flush();
        return $this->redirect($this->generateUrl('pickset_edit', array(
            'id' => $pickSet->getId()
        )));
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
        $em = $this->get('doctrine.orm.entity_manager');
        $pickSet = $em->getRepository('CollegeCrazies\Bundle\MainBundle\Entity\PickSet')->find($id);
        if (!$pickSet) {
            throw new \NotFoundHttpException(sprintf('There was no pickSet with id = %s', $id));
        }
        return $pickSet;
    }
}
