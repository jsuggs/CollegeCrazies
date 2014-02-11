<?php

namespace SofaChamps\Bundle\CoreBundle\Entity;

interface GameInterface
{
    public function getId();
    public function setId($id);
    public function setHomeTeam(TeamInterface $team);
    public function getHomeTeam();
    public function setAwayTeam(TeamInterface $team);
    public function getAwayTeam();
    public function setHomeTeamScore($score);
    public function getHomeTeamScore();
    public function setAwayTeamScore($score);
    public function getAwayTeamScore();
    public function setGameDate($date);
    public function getGameDate();
}
