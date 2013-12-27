<?php

namespace SofaChamps\Bundle\MarchMadnessBundle\Bracket;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket;
use SofaChamps\Bundle\BracketBundle\Bracket\AbstractBracketManager;

/**
 * @DI\Service("sofachamps.mm.bracket_manager")
 */
class BracketManager extends AbstractBracketManager
{
    protected function getGameClass()
    {
        return 'SofaChamps\Bundle\MarchMadnessBundle\Entity\Game';
    }

    public function createBracket($season)
    {
        $bracket = new Bracket($season);

        $this->om->persist($bracket);

        return $bracket;
    }
}
