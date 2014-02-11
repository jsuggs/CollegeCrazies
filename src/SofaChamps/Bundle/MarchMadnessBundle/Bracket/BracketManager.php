<?php

namespace SofaChamps\Bundle\MarchMadnessBundle\Bracket;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BracketBundle\Bracket\AbstractBracketManager;
use SofaChamps\Bundle\BracketBundle\Entity\AbstractBracket;
use SofaChamps\Bundle\BracketBundle\Entity\AbstractBracketGame;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket;

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

    public function createBracketGames(AbstractBracket $bracket)
    {
    }
}
