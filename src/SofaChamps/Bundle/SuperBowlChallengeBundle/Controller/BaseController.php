<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Controller;

use SofaChamps\Bundle\CoreBundle\Controller\CoreController;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Config;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Pick;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Question;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Result;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Form\ConfigFormType;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Form\QuestionFormType;

class BaseController extends CoreController
{
    protected function getPickForm(Pick $pick = null)
    {
        return $this->createForm('sbc_pick', $pick);
    }

    protected function getConfigForm(Config $config)
    {
        return $this->createForm(new ConfigFormType(), $config);
    }

    protected function getResultForm(Result $result)
    {
        return $this->createForm('sbc_result', $result);
    }

    protected function getQuestionForm(Question $question)
    {
        return $this->createForm(new QuestionFormType(), $question);
    }

    protected function getUserPick(User $user, $year)
    {
        $pick = $this
            ->getRepository('SofaChampsSuperBowlChallengeBundle:Pick')
            ->findOneBy(array(
                'year' => $year,
                'user' => $user,
            ));

        if (!$pick) {
            $pick = new Pick($year);
            $pick->setUser($user);
        }

        return $pick;
    }

    protected function getConfig($year)
    {
        $config = $this
            ->getRepository('SofaChampsSuperBowlChallengeBundle:Config')
            ->find($year);

        if (!$config) {
            $config = new Config($year);
            $config->setStartTime(new \DateTime());
            $config->setCloseTime(new \DateTime());

            // Default the 4 bonus questions
            for ($index = 4; $index >= 1; $index--) {
                $question = new Question();
                $question->setYear($year);
                $question->setIndex($index);
                $question->setText(sprintf('Bonus Question %d', $index));

                $config->addQuestion($question);
            }
        }

        return $config;
    }

    protected function getResult($year)
    {
        $result = $this
            ->getRepository('SofaChampsSuperBowlChallengeBundle:Result')
            ->find($year);

        return $result ?: new Result($year);
    }

    protected function getPickManager()
    {
        return $this->get('sofachamps.superbowlchallenge.pickmanager');
    }
}
