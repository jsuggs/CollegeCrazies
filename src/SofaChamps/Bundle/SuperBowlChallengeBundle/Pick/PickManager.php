<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Pick;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\CoreBundle\Util\Math\SigmaUtils;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Config;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Pick;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Result;

/**
 * PickManager
 *
 * @DI\Service("sofachamps.superbowlchallenge.pickmanager")
 */
class PickManager
{
    protected $em;

    /**
     * @DI\InjectParams({
     *      "em" = @DI\Inject("doctrine.orm.default_entity_manager")
     * })
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function scorePicks($year)
    {
        $picks = $this->getPicks($year);
        $config = $this->getConfig($year);
        $result = $this->getResult($year);

        foreach ($picks as $pick) {
            PickScorer::scorePick($pick, $result, $config);
        }

        $config->setScoresCalculated(true);

        $this->em->flush();
    }

    public function picksOpen($year)
    {
        $config = $this->getConfig($year);

        if (!$config) {
            return false;
        }

        $now = new \DateTime();

        return $now > $config->getStartTime() && $now < $config->getCloseTime();
    }

    public function picksEditable($year)
    {
        $config = $this->getConfig($year);

        if (!$config) {
            return false;
        }

        $now = new \DateTime();

        return $now > $config->getStartTime();
    }

    public function picksViewable($year)
    {
        $config = $this->getConfig($year);

        if (!$config) {
            return false;
        }

        $now = new \DateTime();

        return $now > $config->getCloseTime();
    }

    public function gameAvailable($year)
    {
        $config = $this->getConfig($year);

        if (!$config) {
            return false;
        }

        $now = new \DateTime();

        return $now < $config->getCloseTime()->modify('+30 days');
    }

    public function getCurrentYear()
    {
        // TODO
        return 2018;
    }

    protected function getConfig($year)
    {
        return $this->em->getRepository('SofaChampsSuperBowlChallengeBundle:Config')->find($year);
    }

    protected function getResult($year)
    {
        return $this->em->getRepository('SofaChampsSuperBowlChallengeBundle:Result')->find($year);
    }

    protected function getPicks($year)
    {
        return $this->em->getRepository('SofaChampsSuperBowlChallengeBundle:Pick')->findByYear($year);
    }
}
