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
 * @Route("/{season}/pickset")
 */
class PickController extends BaseController
{
    /**
     * @Route("/list", name="pickset_list")
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function listAction($season)
    {
        return array(
            'season' => $season,
            'pickSets' => $this->getUser()->getPickSetsForSeason($season),
        );
    }

    /**
     * @Route("/manage", name="pickset_manage")
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function manageAction($season)
    {
        $user = $this->getUser();
        $pickSets = $user->getPickSetsForSeason($season);

        if (count($pickSets) === 0) {
            return $this->redirect($this->generateUrl('pickset_new', array(
                'season' => $season,
            )));
        }

        $request = $this->getRequest();

        if ($request->getMethod() === 'POST') {
            if ($this->picksLocked()) {
                $this->addMessage('warning', 'You can\' update your leagues after picks lock');
            } else {
                $em = $this->getEntityManager();
                $conn = $em->getConnection();
                // Delete all of the users picksets
                // TODO - Move to repo
                $conn->executeUpdate('DELETE FROM pickset_leagues WHERE pickset_id IN (SELECT id FROM picksets WHERE user_id = ? AND season = ?)', array($user->getId(), $season));

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
            'leagues' => $user->getLeaguesForSeason($season),
            'season' => $season,
        );
    }

    /**
     * @Route("/new", name="pickset_new")
     * @Secure(roles="ROLE_USER")
     * @Template("SofaChampsBowlPickemBundle:Pick:new.html.twig")
     */
    public function newPickAction($season)
    {
        // No more picksets after picks lock
        if ($this->picksLocked()) {
            $this->addMessage('warning', 'Sorry, the fun is over...no more picksets');
            return $this->redirect($this->generateUrl('bp_home', array(
                'season' => $season,
            )));
        }

        $user = $this->getUser();
        $leagueId = $this->getSession()->get('auto_league_assoc') ?: $this->getRequest()->get('leagueId');
        $league = $leagueId
            ? $this->findLeague($leagueId)
            : null;

        $pickSet = $this->getPicksetManager()->createUserPickset($user, $season, $league);

        if ($league) {
            $this->addMessage('success', sprintf('Pickset assigned to league "%s"', $league->getName()));
            $this->getEntityManager()->flush();

            return $this->redirect($this->generateUrl('pickset_edit', array(
                'season' => $season,
                'picksetId' => $pickSet->getId(),
            )));
        }

        $form = $this->getPickSetForm($pickSet);

        return array(
            'season' => $season,
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
    public function editPickAction(PickSet $pickSet, $season)
    {
        if ($this->get('session')->get('auto_league_create')) {
            $this->get('session')->remove('auto_league_create');
        } else {
            if (count($pickSet->getLeagues()) === 0) {
                $this->addMessage('info', 'This pickset is not associated with a league');
            }
        }

        $pickSet = $this->getRepository('SofaChampsBowlPickemBundle:PickSet')->getPopulatedPickSet($pickSet);

        $form = $this->getPickSetForm($pickSet);
        return array(
            'form' => $form->createView(),
            'pickSet' => $pickSet,
            'season' => $season,
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
    public function viewPickNoLeagueAction(PickSet $pickSet, $season)
    {
        return array(
            'season' => $season,
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
    public function comparePickSetAction(PickSet $pickSet1, PickSet $pickSet2, $season)
    {
        $games = $this->getRepository('SofaChampsBowlPickemBundle:Game')->findAllOrderedByDate($season);

        return array(
            'games' => $games,
            'pickSet1' => $pickSet1,
            'pickSet2' => $pickSet2,
        );
    }

    /**
     * @Route("/create", name="pickset_create")
     * @Secure(roles="ROLE_USER")
     * @Method({"POST"})
     * @Template("SofaChampsBowlPickemBundle:Pick:new.html.twig")
     */
    public function createPickAction($season)
    {
        // No more picksets after picks lock
        if ($this->picksLocked()) {
            $this->addMessage('warning', 'Sorry, the fun is over...no more picksets');
            return $this->redirect($this->generateUrl('bp_home', array(
                'season' => $season,
            )));
        }

        $user = $this->getUser();
        $league = $this->getRequest()->query->has('leagueId')
            ? $this->findLeague($this->getRequest()->get('leagueId'))
            : null;

        $pickSet = $this->getPicksetManager()->createUserPickset($user, $season, $league);
        $form = $this->getPickSetForm($pickSet);
        $request = $this->getRequest();
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getEntityManager();
            $em->persist($pickSet);
            $em->flush();

            return $this->redirect($this->generateUrl('pickset_edit', array(
                'season' => $season,
                'picksetId' => $pickSet->getId(),
            )));
        }

        $this->addMessage('warning', 'There was an error creating your pickset');

        return array(
            'form' => $form->createView(),
            'season' => $season,
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
    public function updatePickAction(PickSet $pickSet, $season)
    {
        $form = $this->getPickSetForm($pickSet);
        $form->bind($this->getRequest());
        if ($form->isValid()) {
            $em = $this->getEntityManager();

            $em->persist($pickSet);
            $em->flush();

            $this->addMessage('success', 'Pickset successfully updated');

            return $this->redirect($this->generateUrl('pickset_edit', array(
                'season' => $season,
                'picksetId' => $pickSet->getId(),
            )));
        }

        return array(
            'form' => $form->createView(),
            'pickSet' => $pickSet,
            'season' => $season,
        );
    }

    /**
     * @Route("/data/{leagueId}/{pickSetId}", name="pickset_data")
     * @Secure(roles="ROLE_USER")
     * @ParamConverter("league", class="SofaChampsBowlPickemBundle:League", options={"id" = "leagueId"})
     * @ParamConverter("pickSet", class="SofaChampsBowlPickemBundle:PickSet", options={"id" = "pickSetId"})
     */
    public function dataAction(League $league, Pickset $pickSet, $season)
    {
        $data = $this->getRepository('SofaChampsBowlPickemBundle:PickSet')
            ->getPickDistribution($pickSet, $league, $season);

        return new JsonResponse($data);
    }

    private function getPickSetForm(PickSet $pickSet)
    {
        return $this->createForm(new PickSetFormType(), $pickSet);
    }

    protected function getPicksetManager()
    {
        return $this->container->get('sofachamps.bp.pickset_manager');
    }
}
