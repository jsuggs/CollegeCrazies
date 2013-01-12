<?php

namespace SofaChamps\Bundle\QuestionBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\QuestionBundle\Form\QuestionFormType;
use SofaChamps\Bundle\QuestionBundle\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminController extends Controller
{
    /**
     * @Route("/list", name="question_list")
     * @Secure(roles="ROLE_ADMIN")
     * @Template
     */
    public function listAction()
    {
        $questions = $this->get('doctrine.orm.entity_manager')
            ->getRepository('SofaChampsQuestionBundle:Question')
            ->findAll();

        return array(
            'questions' => $questions
        );
    }

    /**
     * @Route("/new", name="question_new")
     * @Secure(roles="ROLE_ADMIN")
     * @Template
     */
    public function newAction()
    {
        $form = $this->getQuestionForm();

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/create", name="question_create")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("SofaChampsQuestionBundle:Question:new.html.twig")
     */
    public function createAction()
    {
        $form = $this->getQuestionForm();
        $form->bindRequest($this->getRequest());

        if ($form->isValid()) {
            $question = $form->getData();
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($question);
            $em->flush($question);
            $this->get('session')->getFlashBag()->set('success', 'Question Created');
        } else {
            return array(
                'form' => $form->createView(),
            );
        }

        return $this->redirect($this->generateUrl('question_list'));
    }

    /**
     * @Route("/edit/{id}", name="question_edit")
     * @Secure(roles="ROLE_ADMIN")
     * @Template
     */
    public function editAction($id)
    {
        $question = $this->findQuestion($id);
        $form = $this->getQuestionForm($question);

        return array(
            'form' => $form->createView(),
            'question' => $question,
        );
    }

    /**
     * @Route("/update/{id}", name="question_update")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("SofaChampsQuestionBundle:Question:edit.html.twig")
     */
    public function updateAction($id)
    {
        $question = $this->findQuestion($id);
        $form = $this->getQuestionForm($question);
        $form->bindRequest($this->getRequest());

        if ($form->isValid()) {
            $this->get('doctrine.orm.entity_manager')->flush($question);
            $this->get('session')->getFlashBag()->set('success', 'Question updated');

            return $this->redirect($this->generateUrl('question_edit', array(
                'id' => $id,
            )));
        }

        $this->get('session')->getFlashBag()->set('error', 'Error editing the question');

        return array(
            'form' => $form->createView(),
            'question' => $question,
        );
    }

    protected function findQuestion($id)
    {
        $question = $this
            ->get('doctrine.orm.entity_manager')
            ->getRepository('SofaChampsQuestionBundle:Question')
            ->find((int) $id);

        if (!$question) {
            throw new NotFoundHttpException(sprintf('There was no question with id = %s', $id));
        }

        return $question;
    }

    protected function getQuestionForm(Question $question = null)
    {
        return $this->createForm(new QuestionFormType(), $question);
    }
}
