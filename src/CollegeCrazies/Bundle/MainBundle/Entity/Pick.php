<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use CollegeCrazies\Bundle\MainBundle\Entity\Team;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Users Pick for a single game
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="picks"
 * )
 */
class Pick {

    /**
     * @ORM\Id
     * @ORM\Column
     */
    protected $id;

    protected $user;
    protected $league;
    protected $game;
    protected $team;
    protected $confidence;

    public function setTeam(Team $team)
    {
        if ($team != $game->getHomeTeam() || $team != $game->getAwayTeam()) {
            die('bad');
        }
        $this->team = $team;
    }
}
