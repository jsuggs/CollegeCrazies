<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Pick;

use Doctrine\ORM\EntityManager;
use SofaChamps\Bundle\CoreBundle\Util\Math\SigmaUtils;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Config;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Pick;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Result;

class PickManager
{
    protected $em;

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

        $this->em->flush();
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
