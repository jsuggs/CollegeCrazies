<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\BowlPickemBundle\Entity\League;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Pick;
use SofaChamps\Bundle\BowlPickemBundle\Entity\PickSet;
use SofaChamps\Bundle\BowlPickemBundle\Form\PickFormType;
use SofaChamps\Bundle\BowlPickemBundle\Form\PickSetFormType;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/pickset")
 */
class PickController extends BaseController
{
    /**
     * @Route("/list", name="pickset_list")
     * @Secure(roles="ROLE_USER")
     * @Template
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
                $this->addMessage('warning', 'You can\' update your leagues after picks lock');
            } else {
                $em = $this->getEntityManager();
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
                $this->addMessage('success', 'Picksets have now been assigned to your Leagues');
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
     * @Template("SofaChampsBowlPickemBundle:Pick:new.html.twig")
     */
    public function newPickAction()
    {
        // No more picksets after picks lock
        if ($this->picksLocked()) {
            $this->addMessage('warning', 'Sorry, the fun is over...no more picksets');
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

        $em = $this->getEntityManager();
        $games = $this->getRepository('SofaChampsBowlPickemBundle:Game')->findAllOrderedByDate();
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
                $this->addMessage('success', sprintf('Pickset assigned to league "%s"', $league->getName()));

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
     * @ParamConverter("pickSet", class="SofaChampsBowlPickemBundle:PickSet", options={"id" = "picksetId"})
     * @SecureParam(name="pickSet", permissions="EDIT")
     * @Template("SofaChampsBowlPickemBundle:Pick:edit.html.twig")
     */
    public function editPickAction(PickSet $pickSet)
    {
        if ($this->get('session')->get('auto_league_create')) {
            $this->get('session')->remove('auto_league_create');
        } else {
            if (count($pickSet->getLeagues()) === 0) {
                $this->addMessage('info', 'This pickset is not associated with a league');
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
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @ParamConverter("pickSet", class="SofaChampsBowlPickemBundle:PickSet", options={"id" = "picksetId"})
     * @SecureParam(name="pickSet", permissions="VIEW")
     * @Template("SofaChampsBowlPickemBundle:Pick:view.html.twig")
     */
    public function viewPickAction(League $league, PickSet $pickSet)
    {
        $projectedFinishStats = $this->getRepository('SofaChampsBowlPickemBundle:PickSet')->getProjectedFinishStats($pickSet, $league);

        return array(
            'pickSet' => $pickSet,
            'league' => $league,
            'projectedFinishStats' => $projectedFinishStats,
        );
    }

    /**
     * @Route("/view/{picksetId}", name="pickset_view_noleague")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("pickSet", class="SofaChampsBowlPickemBundle:PickSet", options={"id" = "picksetId"})
     * @Template("SofaChampsBowlPickemBundle:Pick:view.html.twig")
     */
    public function viewPickNoLeagueAction(PickSet $pickSet)
    {
        return array(
            'pickSet' => $pickSet,
        );
    }

    /**
     * @Route("/compare/{picksetId1}/{picksetId2}", name="pickset_compare")
     * @ParamConverter("pickSet1", class="SofaChampsBowlPickemBundle:PickSet", options={"id" = "picksetId1"})
     * @ParamConverter("pickSet2", class="SofaChampsBowlPickemBundle:PickSet", options={"id" = "picksetId2"})
     * @SecureParam(name="pickSet1", permissions="VIEW")
     * @SecureParam(name="pickSet1", permissions="VIEW")
     * @Secure(roles="ROLE_USER")
     * @Template("SofaChampsBowlPickemBundle:Pick:compare.html.twig")
     */
    public function comparePickSetAction(PickSet $pickSet1, PickSet $pickSet2)
    {
        $games = $this->getRepository('SofaChampsBowlPickemBundle:Game')->findAllOrderedByDate();

        return array(
            'games' => $games,
            'pickSet1' => $pickSet1,
            'pickSet2' => $pickSet2,
        );
    }

    /**
     * @Route("/create", name="pickset_create")
     * @Secure(roles="ROLE_USER")
     * @Template("SofaChampsBowlPickemBundle:Pick:new.html.twig")
     */
    public function createPickAction()
    {
        // No more picksets after picks lock
        if ($this->picksLocked()) {
            $this->addMessage('warning', 'Sorry, the fun is over...no more picksets');
            return $this->redirect('/');
        }

        $pickSet = new PickSet();
        $form = $this->getPickSetForm($pickSet);
        $request = $this->getRequest();
        $form->bindRequest($request);

        if ($form->isValid()) {
            $user = $this->getUser();
            $pickSet->setUser($user);

            $em = $this->getEntityManager();

            $user->addPickSet($pickSet);
            $em->persist($user);
            $em->persist($pickSet);
            $em->flush();

            return $this->redirect($this->generateUrl('pickset_edit', array(
                'picksetId' => $pickSet->getId(),
            )));
        }

        $this->addMessage('warning', 'There was an error creating your pickset');

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/update/{picksetId}", name="pickset_update")
     * @Secure(roles="ROLE_USER")
     * @Method({"POST"})
     * @ParamConverter("pickSet", class="SofaChampsBowlPickemBundle:PickSet", options={"id" = "picksetId"})
     * @SecureParam(name="pickSet", permissions="EDIT")
     * @Template("SofaChampsBowlPickemBundle:Pick:edit.html.twig")
     */
    public function updatePickAction(PickSet $pickSet)
    {
        $form = $this->getPickSetForm($pickSet);
        $form->bindRequest($this->getRequest());
        if ($form->isValid()) {
            $em = $this->getEntityManager();

            $em->persist($pickSet);
            $em->flush();

            $this->addMessage('success', 'Pickset successfully updated');

            return $this->redirect($this->generateUrl('pickset_edit', array(
                'picksetId' => $pickSet->getId(),
            )));
        }

        return array(
            'form' => $form->createView(),
            'pickSet' => $pickSet,
        );
    }

    /**
     * @Route("/data/{leagueId}/{pickSetId}", name="pickset_data")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @ParamConverter("pickSet", class="SofaChampsBowlPickemBundle:PickSet", options={"id" = "pickSetId"})
     */
    public function dataAction(League $league, Pickset $pickSet)
    {

        $data = $this->getRepository('SofaChampsBowlPickemBundle:PickSet')
            ->getPickDistribution($pickSet, $league);

        return new JsonResponse($data);
    }

    private function getPickSetForm(PickSet $pickSet)
    {
        return $this->createForm(new PickSetFormType(), $pickSet);
    }
}
