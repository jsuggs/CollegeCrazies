<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Season;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BowlPickemBundle\Entity\PickSet;
use SofaChamps\Bundle\BowlPickemBundle\Entity\Season;
use SofaChamps\Bundle\CoreBundle\Util\Math\SigmaUtils;

/**
 * SeasonCopier
 * Copies data between seasons
 *
 * @DI\Service("sofachamps.bp.season_copier")
 */
class SeasonCopier
{
    private $om;

    /**
     * @DI\InjectParams({
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager"),
     * })
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function copyGames(Season $fromSeason, Season $toSeason)
    {
        $games = $this->om->getRepository('SofaChampsBowlPickemBundle:Game')->findAllOrderedByDate($fromSeason);
        foreach ($games as $game) {
            $newGame = clone $game;
            $newGame->setSeason($toSeason);
            $newGame->setHomeTeamScore(null);
            $newGame->setAwayTeamScore(null);

            $gameDate = $newGame->getGameDate();
            $yearDiff = $toSeason - $gameDate->format('Y');
            $gameDate->modify(sprintf('%d years', $yearDiff));
            $newGame->setGameDate($gameDate);
            $this->om->persist($newGame);
        }
        $this->om->flush();
    }
}
