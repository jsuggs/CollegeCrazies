<?php

namespace CollegeCrazies\Bundle\MainBundle\DataFixtures\ORM;

use CollegeCrazies\Bundle\MainBundle\Entity\Game;
use CollegeCrazies\Bundle\MainBundle\Entity\League;
use CollegeCrazies\Bundle\MainBundle\Entity\Pick;
use CollegeCrazies\Bundle\MainBundle\Entity\PickSet;
use CollegeCrazies\Bundle\MainBundle\Entity\Team;
use CollegeCrazies\Bundle\MainBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This was broken out into multiple fixtures, but it is one atomic operation
 * Also, the references were a pain to maintain across fixtures.
 */
class LoadTestingData implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        // Users
        $jsuggs     = $this->createUser($manager, 'jsuggs',     'jsuggs@gmail.com',         array('ROLE_ADMIN'));
        $granite777 = $this->createUser($manager, 'granite777', 'dustin.whitter@gmail.com', array('ROLE_ADMIN'));
        $commish1   = $this->createUser($manager, 'commish1',   'commish1@test.com');
        $commish2   = $this->createUser($manager, 'commish2',   'commish2@test.com');

        $users = array();
        for ($x = 1; $x < 10; $x++) {
            $users[] = $this->createUser($manager, "test$x", "test$x@test.com");
        }

        $manager->flush();

        // Teams
        $bama  = $this->createTeam($manager, 'BAMA', 'Alabama');
        $ore   = $this->createTeam($manager, 'ORE',  'Oregon');
        $ohio  = $this->createTeam($manager, 'OHIO', 'Ohio');
        $wku   = $this->createTeam($manager, 'WKU',  'Western Kentucky');
        $pitt  = $this->createTeam($manager, 'PITT', 'Pittsburgh');
        $tenn  = $this->createTeam($manager, 'TENN', 'Tennessee');
        $tex   = $this->createTeam($manager, 'TEX',  'Texas');
        $txam  = $this->createTeam($manager, 'TXAM', 'Texas A&M');
        $kst   = $this->createTeam($manager, 'KST',  'Kansas State');
        $nd    = $this->createTeam($manager, 'ND',   'Notre Dame');
        $lsu   = $this->createTeam($manager, 'LSU',  'Lousianna State University');
        $ou    = $this->createTeam($manager, 'OU',   'Oklahoma University');
        $fsu   = $this->createTeam($manager, 'FSU',  'Florida State University');
        $lou   = $this->createTeam($manager, 'LOU',  'Lousiville');
        $osu   = $this->createTeam($manager, 'ORST', 'Oregon State University');
        $neb   = $this->createTeam($manager, 'NEB',  'Nebraska');
        $mich  = $this->createTeam($manager, 'MICH', 'Michigan');
        $uga   = $this->createTeam($manager, 'UGA',  'Georgia');

        $manager->flush();

        // Games
        $games = array();
        $games[] = $bcs     = $this->createGame($manager, $bama, $ore,  'BCS Championship', 'ESPN', new \DateTime('2013-01-07 18:30:00'), 7.5,  51);
        $games[] = $godaddy = $this->createGame($manager, $ohio, $wku,  'GoDaddy.com',      'NBC',  new \DateTime('2013-01-06 12:30:00'), 3,    39);
        $games[] = $bbva    = $this->createGame($manager, $pitt, $tenn, 'BBVA Compass',     'ABC',  new \DateTime('2013-01-05 18:30:00'), 6,    45);
        $games[] = $att     = $this->createGame($manager, $txam, $tex,  'AT&T Cotton',      'ABC',  new \DateTime('2013-01-04 18:30:00'), 9.5,  63);
        $games[] = $fies    = $this->createGame($manager, $kst,  $nd,   'Tostitos Fiesta',  'ESPN', new \DateTime('2013-01-03 18:35:00'), 2.5,  39);
        $games[] = $allst   = $this->createGame($manager, $lsu,  $ou,   'Allstate Sugar',   'NCB',  new \DateTime('2013-01-02 18:00:00'), 10.5, 47);
        $games[] = $disc    = $this->createGame($manager, $fsu,  $lou,  'Discover Orange',  'ABC',  new \DateTime('2013-01-01 19:30:00'), 14.5, 74);
        $games[] = $rose    = $this->createGame($manager, $osu,  $neb,  'Rose Bowl',        'ESPN', new \DateTime('2013-01-01 14:00:00'), 6.5,  57);
        $games[] = $cap1    = $this->createGame($manager, $uga,  $mich, 'Capital One',      'ESPN', new \DateTime('2013-01-01 11:25:00'), 10.5, 62);

        $manager->flush();

        // Leagues
        $pub1 = $this->createLeague($manager, 'Public 1',  'password', true,  array_merge($users, array($jsuggs, $granite777, $commish1)), array($jsuggs, $commish1));
        $pub2 = $this->createLeague($manager, 'Public 2',  'password', true,  array_merge($users, array($jsuggs, $commish2)), array($jsuggs, $commish2));
        $pub3 = $this->createLeague($manager, 'Public 3',  'password', true,  array_merge($users, array($granite777)));
        $pri1 = $this->createLeague($manager, 'Private 1', 'password', false, array($jsuggs, $granite777, $commish1), array($commish1));

        $manager->flush();

        // PickSets
        $this->createPickSet($manager, 'jsuggs - Awesome Sauce',      $pub1, $jsuggs,     $games);
        $this->createPickSet($manager, 'jsuggs - Awesome Sauce',      $pub2, $jsuggs,     $games);
        $this->createPickSet($manager, 'granite777 - Mediocre Picks', $pub1, $granite777, $games);
        $this->createPickSet($manager, 'granite777 - Mediocre Picks', $pub3, $granite777, $games);
        $this->createPickSet($manager, 'jsuggs - Random Picks',       $pri1, $jsuggs,     $games);
        $this->createPickSet($manager, 'granite777 - Random Picks',   $pri1, $granite777, $games);
        $this->createPickSet($manager, 'commish1 - Boom',             $pub1, $commish1,   $games);
        $this->createPickSet($manager, 'commish2 - Boom',             $pub2, $commish2,   $games);
        foreach ($users as $user) {
            $this->createPickSet($manager, sprintf('%s - Pub1 Picks', $user->getUsername()), $pub1, $user, $games);
        }
        foreach ($users as $user) {
            $this->createPickSet($manager, sprintf('%s - Pub2 Picks', $user->getUsername()), $pub2, $user, $games);
        }
        foreach ($users as $user) {
            $this->createPickSet($manager, sprintf('%s - Pub3 Picks', $user->getUsername()), $pub3, $user, $games);
        }

        $manager->flush();
    }

    private function createUser(ObjectManager $manager, $username, $email, $roles = array())
    {
        $userManager = $this->container->get('fos_user.user_manager');

        $user = $userManager->createUser();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPlainPassword('password');
        $user->setEnabled(true);
        $user->setRoles($roles);
        $userManager->updateCanonicalFields($user);
        $userManager->updatePassword($user);

        $manager->persist($user);

        return $user;
    }

    private function createTeam(ObjectManager $manager, $id, $name)
    {
        $team = new Team();
        $team->setId($id);
        $team->setName($name);

        $manager->persist($team);

        return $team;
    }

    private function createGame(ObjectManager $manager, Team $homeTeam, Team $awayTeam, $name, $network, \DateTime $gameDate, $spread, $overUnder)
    {
        $game = new Game();
        $game->setHomeTeam($homeTeam);
        $game->setAwayTeam($awayTeam);
        $game->setName($name);
        $game->setNetwork($network);
        $game->setGameDate($gameDate);
        $game->setSpread($spread);
        $game->setOverunder($overUnder);

        $manager->persist($game);

        return $game;
    }

    private function createLeague(ObjectManager $manager, $name, $password, $public, $users, $commissioners = array())
    {
        $league = new League();
        $league->setName($name);
        $league->setPassword($password);
        $league->setPublic($public);
        $league->setUsers($users);
        $league->setLocked(false);
        $league->setCommissioners($commissioners);

        $manager->persist($league);

        return $league;
    }

    private function createPickSet(ObjectManager $manager, $name, League $league, User $user, $games)
    {
        $pickSet = new PickSet();
        $pickSet->setName($name);
        $pickSet->addLeague($league);
        $pickSet->setUser($user);
        $pickSet->setTiebreakerHomeTeamScore(floor(rand(10, 51)));
        $pickSet->setTiebreakerAwayTeamScore(floor(rand(3, 45)));

        $picks = $this->createRandomPicks($manager, $pickSet, $games);
        $pickSet->setPicks($picks);

        $manager->persist($pickSet);

        return $pickSet;
    }

    private function createRandomPicks(ObjectManager $manager, PickSet $pickSet, $games)
    {
        $picks = array();

        // Randomize the confidence
        shuffle($games);

        foreach ($games as $idx => $game) {
            $pick = $this->createRandomPick($game, $pickSet, $idx + 1);

            $manager->persist($pick);
            $picks[] = $pick;
        }

        return $picks;
    }

    private function createRandomPick(Game $game, PickSet $pickSet, $confidence)
    {
        $pick = new Pick();
        $pick->setPickSet($pickSet);
        $pick->setConfidence($confidence);
        $pick->setGame($game);
        $pick->setTeam(rand(0, 1) < .5 ? $game->getHomeTeam() : $game->getAwayTeam());

        return $pick;
    }
}
