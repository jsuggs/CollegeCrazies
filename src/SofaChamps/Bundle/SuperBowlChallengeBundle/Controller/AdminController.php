<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Question;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin")
 */
class AdminController extends BaseController
{
    /**
     * @Route("/config/{year}", name="sbc_admin_config")
     * @Secure(roles="ROLE_ADMIN")
     * @Template
     */
    public function configAction($year)
    {
        $config = $this->getConfig($year);
        $form = $this->getConfigForm($config);
        $request = $this->getRequest();

        if ($request->getMethod() === 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $config = $form->getData();

                $em = $this->getEntityManager();
                $em->persist($config);
                $em->flush();

                $this->addMessage('success', 'Config updated');
            }
        }

        return array(
            'config' => $config,
            'year' => $year,
            'form' => $form->createView(),
            'user' => $this->getUser(),
        );
    }

    /**
     * @Route("/result/{year}", name="sbc_admin_result")
     * @Secure(roles="ROLE_ADMIN")
     * @Template
     */
    public function resultAction($year)
    {
        $result = $this->getResult($year);
        $form = $this->getResultForm($result);
        $request = $this->getRequest();
        $config = $this->getConfig($year);

        if ($request->getMethod() === 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $result = $form->getData();

                $em = $this->getEntityManager();
                $em->persist($result);
                $em->flush($result);

                $this->addMessage('success', 'Result updated');
            }
        }

        return array(
            'config' => $config,
            'result' => $result,
            'year' => $year,
            'form' => $form->createView(),
            'user' => $this->getUser(),
        );
    }

    /**
     * @Route("/question/{questionId}", name="sbc_admin_question")
     * @ParamConverter("question", class="SofaChampsSuperBowlChallengeBundle:Question", options={"id" = "questionId"})
     * @Secure(roles="ROLE_ADMIN")
     * @Template
     */
    public function questionAction(Question $question)
    {
        $form = $this->getQuestionForm($question);
        $request = $this->getRequest();

        if ($request->getMethod() === 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $question = $form->getData();

                $this->getEntityManager()->flush();

                $this->addMessage('success', 'Question updated');
            }
        }

        return array(
            'question' => $question,
            'year' => $this->get('config.curyear'),
            'form' => $form->createView(),
            'user' => $this->getUser(),
        );
    }
}
