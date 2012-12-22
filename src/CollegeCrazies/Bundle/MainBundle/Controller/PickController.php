<?php

namespace CollegeCrazies\Bundle\MainBundle\Controller;

use CollegeCrazies\Bundle\MainBundle\Form\PickFormType;
use CollegeCrazies\Bundle\MainBundle\Form\PickSetFormType;
use CollegeCrazies\Bundle\MainBundle\Entity\Team;
use CollegeCrazies\Bundle\MainBundle\Entity\User;
use CollegeCrazies\Bundle\MainBundle\Entity\Pick;
use CollegeCrazies\Bundle\MainBundle\Entity\PickSet;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/pickset")
 */
class PickController extends BaseController
{
    /**
     * @Route("/list", name="pickset_list")
     * @Secure(roles="ROLE_USER")
     * @Template()
     */
    public function listAction()
    {
        return array(
            'pickSets' => $this->getUser()->getPickSets(),
        );
    }

    /**
     * @Route("/manage", name="pickset_manage")
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function manageAction()
    {
        $user = $this->getUser();
        $pickSets = $user->getPickSets();

        if (count($pickSets) === 0) {
            return $this->redirect($this->generateUrl('pickset_new'));
        }

        $request = $this->getRequest();

        if ($request->getMethod() === 'POST') {
            if ($this->picksLocked()) {
                $this->get('session')->setFlash('warning', 'You can\' update your leagues after picks lock');
            } else {
                $em = $this->get('doctrine.orm.entity_manager');
                $conn = $em->getConnection();
                // Delete all of the users picksets
                $conn->executeUpdate('DELETE FROM pickset_leagues WHERE pickset_id IN (SELECT id FROM picksets WHERE user_id = ?)', array($user->getId()));

                // This is a semi-hack, not using the form framework
                foreach ($request->request->get('league_pickset') as $leagueId => $pickSetId) {
                    $league = $this->findLeague($leagueId);
                    $pickSet = $this->findPickSet($pickSetId);

                    $league->addPickSet($pickSet);
                }
                $em->flush();
                $this->get('session')->setFlash('success', 'Picksets have now been assigned to your Leagues');
            }
        }

        return array(
            'pickSets' => $pickSets,
            'leagues' => $user->getLeagues(),
        );
    }

    /**
     * @Route("/new", name="pickset_new")
     * @Secure(roles="ROLE_USER")
     * @Template("CollegeCraziesMainBundle:Pick:new.html.twig")
     */
    public function newPickAction()
    {
        // No more picksets after picks lock
        if ($this->picksLocked()) {
            $this->get('session')->setFlash('warning', 'Sorry, the fun is over...no more picksets');
            return $this->redirect('/');
        }

        $user = $this->getUser();

        $pickSet = new PickSet();
        $pickSet->setUser($user);
        $defaultPickSetName = substr(sprintf('%s - %s', $user->getUsername(), count($user->getPicksets()) == 0 ? 'Default Pickset' : sprintf('Pickset #%d', count($user->getPicksets()) + 1)), 0, 40);
        $pickSet->setName($defaultPickSetName);

        if ($this->getRequest()->query->has('leagueId')) {
            $league = $this->findLeague($this->getRequest()->get('leagueId'));
            $pickSet->setLeague($league);
        }

        $em = $this->get('doctrine.orm.entity_manager');
        $games = $em->getRepository('CollegeCraziesMainBundle:Game')->findAllOrderedByDate();
        $idx = count($games);
        foreach ($games as $game) {
            $pick = new Pick();
            $pick->setGame($game);
            $pick->setConfidence($idx);

            $pickSet->addPick($pick);
            $idx--;
        }

        // If the user does not have a pickset already, auto-save
        if (count($user->getPicksets()) == 0) {
            $session = $this->container->get('session');
            if ($session->has('auto_league_assoc')) {
                $league = $this->findLeague($session->get('auto_league_assoc'));
                $this->addUserToLeague($league, $user);
                $league->addPickSet($pickSet);
                $this->get('session')->setFlash('success', sprintf('Pickset assigned to league "%s"', $league->getName()));

                $session->remove('auto_league_assoc');
            }
            $em->persist($pickSet);
            $em->flush();
            $session->set('auto_league_create', true);

            return $this->redirect($this->generateUrl('pickset_edit', array(
                'picksetId' => $pickSet->getId(),
            )));
        }

        $form = $this->getPickSetForm($pickSet);

        return array(
            'form' => $form->createView(),
            'league' => isset($league) ? $league : null,
        );
    }

    /**
     * @Route("/edit/{picksetId}", name="pickset_edit")
     * @Secure(roles="ROLE_USER")
     * @Template("CollegeCraziesMainBundle:Pick:edit.html.twig")
     */
    public function editPickAction($picksetId)
    {
        $pickSet = $this->findPickSet($picksetId, true);
        $user = $this->getUser();

        // TODO Add checks for commish here
        if (!$this->canUserEditPickSet($user, $pickSet)) {
            $this->get('session')->setFlash('error', 'You cannot edit this pickset');
            return $this->redirect('/');
        }

        if ($this->get('session')->get('auto_league_create')) {
            $this->get('session')->remove('auto_league_create');
        } else {
            if (count($pickSet->getLeagues()) === 0) {
                $this->get('session')->setFlash('info', 'This pickset is not associated with a league');
            }
        }

        $form = $this->getPickSetForm($pickSet);
        return array(
            'form' => $form->createView(),
            'pickSet' => $pickSet,
        );
    }

    /**
     * @Route("/view/{leagueId}/{picksetId}", name="pickset_view")
     * @Secure(roles="ROLE_USER")
     * @Template("CollegeCraziesMainBundle:Pick:view.html.twig")
     */
    public function viewPickAction($leagueId, $picksetId)
    {
        $league = $this->findLeague($leagueId);
        $pickSet = $this->findPickSet($picksetId, true);
        $user = $this->getUser();

        if (!$this->canUserViewPickSet($user, $pickSet)) {
            $this->get('session')->setFlash('error','You cannot view another users picks until the league is locked');
            return $this->redirect('/');
        }

        $em = $this->get('doctrine.orm.entity_manager');
        $projectedFinishStats = $em->getRepository('CollegeCraziesMainBundle:PickSet')->getProjectedFinishStats($pickSet, $league);

        return array(
            'pickSet' => $pickSet,
            'league' => $league,
            'projectedFinishStats' => $projectedFinishStats,
        );
    }

    /**
     * @Route("/view/{picksetId}", name="pickset_view_noleague")
     * @Secure(roles="ROLE_USER")
     * @Template("CollegeCraziesMainBundle:Pick:view.html.twig")
     */
    public function viewPickNoLeagueAction($picksetId)
    {
        $pickSet = $this->findPickSet($picksetId, true);
        $user = $this->getUser();

        if (!$this->canUserViewPickSet($user, $pickSet)) {
            $this->get('session')->setFlash('error','You cannot view another users picks until the league is locked');
            return $this->redirect('/');
        }

        return array(
            'pickSet' => $pickSet,
        );
    }

    /**
     * @Route("/create", name="pickset_create")
     * @Secure(roles="ROLE_USER")
     * @Template("CollegeCraziesMainBundle:Pick:new.html.twig")
     */
    public function createPickAction()
    {
        // No more picksets after picks lock
        if ($this->picksLocked()) {
            $this->get('session')->setFlash('warning', 'Sorry, the fun is over...no more picksets');
            return $this->redirect('/');
        }

        $pickSet = new PickSet();
        $form = $this->getPickSetForm($pickSet);
        $request = $this->getRequest();
        $form->bindRequest($request);

        if ($form->isValid()) {
            $user = $this->getUser();
            $pickSet->setUser($user);

            $em = $this->get('doctrine.orm.entity_manager');

            $user->addPickSet($pickSet);
            $em->persist($user);
            $em->persist($pickSet);
            $em->flush();

            return $this->redirect($this->generateUrl('pickset_edit', array(
                'picksetId' => $pickSet->getId(),
            )));
        }

        $this->get('session')->setFlash('warning', 'There was an error creating your pickset');

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/update/{picksetId}", name="pickset_update")
     * @Secure(roles="ROLE_USER")
     * @Template("CollegeCraziesMainBundle:Pick:edit.html.twig")
     */
    public function updatePickAction($picksetId)
    {
        $pickSet = $this->findPickSet($picksetId, true);

        if ($this->picksLocked()) {
            $this->get('session')->setFlash('warning', 'You cannot update picks after they are locked');

            return $this->redirect('/');
        }

        $form = $this->getPickSetForm($pickSet);
        $form->bindRequest($this->getRequest());
        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');

            $em->persist($pickSet);
            $em->flush();

            $this->get('session')->setFlash('success', 'Pickset successfully updated');

            return $this->redirect($this->generateUrl('pickset_edit', array(
                'picksetId' => $picksetId,
            )));
        }

        return array(
            'form' => $form->createView(),
            'league' => $league,
            'pickSet' => $pickSet,
        );
    }

    /**
     * @Route("/data/{leagueId}/{pickSetId}", name="pickset_data")
     * @Secure(roles="ROLE_USER")
     */
    public function dataAction($leagueId, $pickSetId)
    {
        $pickSet = $this->findPickSet($pickSetId, true);
        $league = $this->findLeague($leagueId);

        $data = $this->get('doctrine.orm.entity_manager')
            ->getRepository('CollegeCraziesMainBundle:PickSet')
            ->getPickDistribution($pickSet, $league);

        return new JsonResponse($data);
    }

    private function getPickForm(Pick $pick)
    {
        return $this->createForm(new PickFormType(), $pick);
    }

    private function getPickSetForm(PickSet $pickSet)
    {
        return $this->createForm(new PickSetFormType(), $pickSet);
    }
}
