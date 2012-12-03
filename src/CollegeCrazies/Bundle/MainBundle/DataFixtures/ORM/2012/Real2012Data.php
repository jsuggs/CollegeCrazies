<?php

namespace CollegeCrazies\Bundle\MainBundle\DataFixtures\ORM;

use CollegeCrazies\Bundle\MainBundle\Entity\Game;
use CollegeCrazies\Bundle\MainBundle\Entity\League;
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
class Real2012Data implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        // Admin Users
        $jsuggs     = $this->createUser($manager, 'jsuggs',     'jsuggs@gmail.com',         array('ROLE_ADMIN'));
        $granite777 = $this->createUser($manager, 'granite777', 'dustin.whitter@gmail.com', array('ROLE_ADMIN'));

        $manager->flush();

        // Teams
        $AFA  = $this->createTeam($manager, 'AFA', 'Air Force');
        $AKR  = $this->createTeam($manager, 'AKR', 'Akron');
        $ALA  = $this->createTeam($manager, 'ALA', 'Alabama');
        $ARIZ = $this->createTeam($manager, 'ARIZ', 'Arizona');
        $AZST = $this->createTeam($manager, 'AZST', 'Arizona State');
        $ARK  = $this->createTeam($manager, 'ARK', 'Arkansas');
        $ARST = $this->createTeam($manager, 'ARST', 'Arkansas State');
        $ARMY = $this->createTeam($manager, 'ARMY', 'Army');
        $AUB  = $this->createTeam($manager, 'AUB', 'Auburn');
        $BAST = $this->createTeam($manager, 'BAST', 'Ball State');
        $BAY  = $this->createTeam($manager, 'BAY', 'Baylor');
        $BOST = $this->createTeam($manager, 'BOST', 'Boise State');
        $BC   = $this->createTeam($manager, 'BC', 'Boston College');
        $BGSU = $this->createTeam($manager, 'BGSU', 'Bowling Green');
        $BYU  = $this->createTeam($manager, 'BYU', 'Brigham Young');
        $BUF  = $this->createTeam($manager, 'BUF', 'Buffalo');
        $CAL  = $this->createTeam($manager, 'CAL', 'California');
        $CEMI = $this->createTeam($manager, 'CEMI', 'Central Michigan');
        $CINC = $this->createTeam($manager, 'CINC', 'Cincinnati');
        $CLEM = $this->createTeam($manager, 'CLEM', 'Clemson');
        $COLO = $this->createTeam($manager, 'COLO', 'Colorado');
        $COST = $this->createTeam($manager, 'COST', 'Colorado State');
        $CONN = $this->createTeam($manager, 'CONN', 'Connecticut');
        $DUKE = $this->createTeam($manager, 'DUKE', 'Duke');
        $ECU  = $this->createTeam($manager, 'ECU', 'East Carolina');
        $EMU  = $this->createTeam($manager, 'EMU', 'Eastern Mich.');
        $FAU  = $this->createTeam($manager, 'FAU', 'Fla. Atlantic');
        $FLA  = $this->createTeam($manager, 'FLA', 'Florida');
        $FIU  = $this->createTeam($manager, 'FIU', 'Florida Int\'l');
        $FLST = $this->createTeam($manager, 'FLST', 'Florida State');
        $FRST = $this->createTeam($manager, 'FRST', 'Fresno State');
        $UGA  = $this->createTeam($manager, 'UGA', 'Georgia');
        $GT   = $this->createTeam($manager, 'GT', 'Georgia Tech');
        $HAW  = $this->createTeam($manager, 'HAW', 'Hawaii');
        $HOU  = $this->createTeam($manager, 'HOU', 'Houston');
        $IDA  = $this->createTeam($manager, 'IDA', 'Idaho');
        $ILL  = $this->createTeam($manager, 'ILL', 'Illinois');
        $IND  = $this->createTeam($manager, 'IND', 'Indiana');
        $IOWA = $this->createTeam($manager, 'IOWA', 'Iowa');
        $IAST = $this->createTeam($manager, 'IAST', 'Iowa State');
        $KAN  = $this->createTeam($manager, 'KAN', 'Kansas');
        $KSST = $this->createTeam($manager, 'KSST', 'Kansas State');
        $KEST = $this->createTeam($manager, 'KEST', 'Kent State');
        $UK   = $this->createTeam($manager, 'UK', 'Kentucky');
        $ULL  = $this->createTeam($manager, 'ULL', 'La.-Lafayette');
        $ULM  = $this->createTeam($manager, 'ULM', 'La.-Monroe');
        $LT   = $this->createTeam($manager, 'LT', 'Louisiana Tech');
        $LOUI = $this->createTeam($manager, 'LOUI', 'Louisville');
        $LSU  = $this->createTeam($manager, 'LSU', 'LSU');
        $MARS = $this->createTeam($manager, 'MARS', 'Marshall');
        $MD   = $this->createTeam($manager, 'MD', 'Maryland');
        $MASS = $this->createTeam($manager, 'MASS', 'Massachusetts');
        $MEMP = $this->createTeam($manager, 'MEMP', 'Memphis');
        $MIAF = $this->createTeam($manager, 'MIAF', 'Miami (FL)');
        $MIAO = $this->createTeam($manager, 'MIAO', 'Miami (OH)');
        $MICH = $this->createTeam($manager, 'MICH', 'Michigan');
        $MIST = $this->createTeam($manager, 'MIST', 'Michigan State');
        $MTSU = $this->createTeam($manager, 'MTSU', 'Middle Tenn. State');
        $MINN = $this->createTeam($manager, 'MINN', 'Minnesota');
        $MSST = $this->createTeam($manager, 'MSST', 'Mississippi State');
        $MO   = $this->createTeam($manager, 'MO', 'Missouri');
        $NCST = $this->createTeam($manager, 'NCST', 'N.C. State');
        $NAVY = $this->createTeam($manager, 'NAVY', 'Navy');
        $NEB  = $this->createTeam($manager, 'NEB', 'Nebraska');
        $NEV  = $this->createTeam($manager, 'NEV', 'Nevada');
        $UNM  = $this->createTeam($manager, 'UNM', 'New Mexico');
        $NMSU = $this->createTeam($manager, 'NMSU', 'New Mexico State');
        $UNC  = $this->createTeam($manager, 'UNC', 'North Carolina');
        $UNT  = $this->createTeam($manager, 'UNT', 'North Texas');
        $NIU  = $this->createTeam($manager, 'NIU', 'Northern Illinois');
        $NW   = $this->createTeam($manager, 'N.W.', 'Northwestern');
        $ND   = $this->createTeam($manager, 'ND', 'Notre Dame');
        $OHIO = $this->createTeam($manager, 'OHIO', 'Ohio');
        $OHST = $this->createTeam($manager, 'OHST', 'Ohio State');
        $OKLA = $this->createTeam($manager, 'OKLA', 'Oklahoma');
        $OKST = $this->createTeam($manager, 'OKST', 'Oklahoma State');
        $MISS = $this->createTeam($manager, 'MISS', 'Ole Miss');
        $ORE  = $this->createTeam($manager, 'ORE', 'Oregon');
        $ORST = $this->createTeam($manager, 'ORST', 'Oregon State');
        $PSU  = $this->createTeam($manager, 'PSU', 'Penn State');
        $PITT = $this->createTeam($manager, 'PITT', 'Pittsburgh');
        $PURD = $this->createTeam($manager, 'PURD', 'Purdue');
        $RICE = $this->createTeam($manager, 'RICE', 'Rice');
        $RUT  = $this->createTeam($manager, 'RUT', 'Rutgers');
        $SDSU = $this->createTeam($manager, 'SDSU', 'San Diego State');
        $SJSU = $this->createTeam($manager, 'SJSU', 'San Jose State');
        $SMU  = $this->createTeam($manager, 'SMU', 'Southern Methodist');
        $SOAL = $this->createTeam($manager, 'SOAL', 'South Ala.');
        $SCAR = $this->createTeam($manager, 'SCAR', 'South Carolina');
        $SFLA = $this->createTeam($manager, 'SFLA', 'South Florida');
        $USM  = $this->createTeam($manager, 'USM', 'Southern Miss');
        $STAN = $this->createTeam($manager, 'STAN', 'Stanford');
        $SYRA = $this->createTeam($manager, 'SYRA', 'Syracuse');
        $TCU  = $this->createTeam($manager, 'TCU', 'TCU');
        $TEM  = $this->createTeam($manager, 'TEM', 'Temple');
        $TENN = $this->createTeam($manager, 'TENN', 'Tennessee');
        $TEX  = $this->createTeam($manager, 'TEX', 'Texas');
        $TAMU = $this->createTeam($manager, 'TAMU', 'Texas A&M');
        $TXST = $this->createTeam($manager, 'TXST', 'Texas State');
        $TEXT = $this->createTeam($manager, 'TEXT', 'Texas Tech');
        $TOL  = $this->createTeam($manager, 'TOL', 'Toledo');
        $TROY = $this->createTeam($manager, 'TROY', 'Troy');
        $TULA = $this->createTeam($manager, 'TULA', 'Tulane');
        $TULS = $this->createTeam($manager, 'TULS', 'Tulsa');
        $UAB  = $this->createTeam($manager, 'UAB', 'UAB');
        $UCF  = $this->createTeam($manager, 'UCF', 'UCF');
        $UCLA = $this->createTeam($manager, 'UCLA', 'UCLA');
        $UNLV = $this->createTeam($manager, 'UNLV', 'UNLV');
        $USC  = $this->createTeam($manager, 'USC', 'USC');
        $UTAH = $this->createTeam($manager, 'UTAH', 'Utah');
        $UTST = $this->createTeam($manager, 'UTST', 'Utah State');
        $UTEP = $this->createTeam($manager, 'UTEP', 'UTEP');
        $UTSA = $this->createTeam($manager, 'UTSA', 'UTSA');
        $VAND = $this->createTeam($manager, 'VAND', 'Vanderbilt');
        $UVA  = $this->createTeam($manager, 'UVA', 'Virginia');
        $VT   = $this->createTeam($manager, 'VT', 'Virginia Tech');
        $WAKE = $this->createTeam($manager, 'WAKE', 'Wake Forest');
        $WASH = $this->createTeam($manager, 'WASH', 'Washington');
        $WAST = $this->createTeam($manager, 'WAST', 'Washington State');
        $WVU  = $this->createTeam($manager, 'WVU', 'West Virginia');
        $WEKY = $this->createTeam($manager, 'WEKY', 'Western Ky.');
        $WMU  = $this->createTeam($manager, 'WMU', 'Western Mich.');
        $WIS  = $this->createTeam($manager, 'WIS', 'Wisconsin');
        $WYO  = $this->createTeam($manager, 'WYO', 'Wyoming');

        $manager->flush();

        // Games
	    $this->createGame($manager, $ND, $ALA, 'Discover BCS National Championship Game', 'ESPN', new \DateTime('2013-01-07 20:30:00'), 20, 49, 'Miami, Fla.');
	    $this->createGame($manager, $KEST, $ARST, 'GoDaddy.com', 'ESPN', new \DateTime('2013-01-06 21:0:00'), 3.5, 52.5, 'Mobile, Ala.');
	    $this->createGame($manager, $TOL, $MSST, 'BBVA Compass', 'ESPN', new \DateTime('2013-01-05 13:0:00'), -2.5, 46, 'Birmingham, Ala.');
	    $this->createGame($manager, $TEX, $LSU, 'AT&T Cotton', 'FOX', new \DateTime('2013-01-04 20:0:00'), 14, 60.5, 'Arlington, Texas');
	    $this->createGame($manager, $KSST, $ORE, 'Tostitos Fiesta', 'ESPN', new \DateTime('2013-01-03 20:30:00'), 29, 52.5, 'Glendale, Ariz.');
	    $this->createGame($manager, $FLA, $OKLA, 'Allstate Sugar', 'ESPN', new \DateTime('2013-01-02 20:30:00'), -7, 56, 'New Orleans, La.');
	    $this->createGame($manager, $FLST, $RUT, 'Discover Orange', 'ESPN', new \DateTime('2013-01-01 20:30:00'), 7, 59.5, 'Miami, Fla.');
	    $this->createGame($manager, $STAN, $NEB, 'Rose Bowl Game presented by Vizio', 'ESPN', new \DateTime('2013-01-01 17:0:00'), 4, 55, 'Pasadena, Calif.');
	    $this->createGame($manager, $MICH, $TAMU, 'Capital One', 'TBA', new \DateTime('2013-01-01 13:0:00'), -26.5, 55, 'Orlando, Fla.');
	    $this->createGame($manager, $NW, $SCAR, 'Outback', 'TBA', new \DateTime('2013-01-01 13:0:00'), 2, 43.5, 'Tampa, Fla.');
	    $this->createGame($manager, $MINN, $IAST, 'Heart of Dallas', 'ESPNU', new \DateTime('2013-01-01 12:0:00'), -31.5, 55.5, 'Dallas, Texas');
	    $this->createGame($manager, $WIS, $VAND, 'TaxSlayer.com Gator', 'ESPN2', new \DateTime('2013-01-01 12:0:00'), -12, 68, 'Jacksonville, Fla.');
	    $this->createGame($manager, $CLEM, $UGA, 'Chick-fil-A', 'ESPN', new \DateTime('2012-12-31 19:30:00'), -11.5, 61, 'Atlanta, Ga.');
	    $this->createGame($manager, $TULS, $ULM, 'AutoZone Liberty', 'ESPN', new \DateTime('2012-12-31 15:30:00'), -7.5, 63.5, 'Memphis, Tenn.');
	    $this->createGame($manager, $GT, $USC, 'Hyundai Sun', 'CBS', new \DateTime('2012-12-31 14:0:00'), -11, 70.5, 'El Paso, Texas');
	    $this->createGame($manager, $NCST, $MISS, 'Franklin American Mortgage Music City', 'ESPN', new \DateTime('2012-12-31 12:0:00'), -7, 59.5, 'Nashville, Tenn.');
	    $this->createGame($manager, $TCU, $MIST, 'Buffalo Wild Wings', 'ESPN', new \DateTime('2012-12-29 22:15:00'), 14.5, 55, 'Tempe, Ariz.');
	    $this->createGame($manager, $OKST, $ORST, 'Valero Alamo', 'ESPN', new \DateTime('2012-12-29 18:45:00'), -27, 55, 'San Antonio, Texas');
	    $this->createGame($manager, $NAVY, $WASH, 'Kraft Fight Hunger', 'ESPN2', new \DateTime('2012-12-29 15:15:00'), -2, 67.5, 'San Francisco, Calif.');
	    $this->createGame($manager, $SYRA, $BAY, 'New Era Pinstripe', 'ESPN2', new \DateTime('2012-12-29 15:15:00'), -12.5, 54.5, 'Bronx, N.Y.');
	    $this->createGame($manager, $SMU, $AFA, 'Bell Helicopter Armed Forces', 'ESPN', new \DateTime('2012-12-29 11:45:00'), -27, 49, 'Fort Worth, Texas');
	    $this->createGame($manager, $PURD, $TEXT, 'Meineke Car Care of Texas', 'ESPN', new \DateTime('2012-12-28 21:0:00'), -21, 55, 'Houston, Texas');
	    $this->createGame($manager, $VT, $LOUI, 'Russell Athletic', 'ESPN', new \DateTime('2012-12-28 17:30:00'), -29, 56, 'Orlando, Fla.');
	    $this->createGame($manager, $MTSU, $LT, 'AdvoCare V100 Independence', 'ESPN', new \DateTime('2012-12-28 14:0:00'), -17, 52.5, 'Shreveport, La.');
	    $this->createGame($manager, $WVU, $UCLA, 'Bridgepoint Education Holiday', 'ESPN', new \DateTime('2012-12-27 21:45:00'), -16.5, 54.5, 'San Diego, Calif.');
	    $this->createGame($manager, $DUKE, $CINC, 'Belk', 'ESPN', new \DateTime('2012-12-27 18:30:00'), -14.5, 52, 'Charlotte, N.C.');
	    $this->createGame($manager, $BGSU, $WEKY, 'Military Bowl Presented By Northrop Grumman', 'ESPN', new \DateTime('2012-12-27 15:0:00'), -28, 55, 'Washington, D.C.');
	    $this->createGame($manager, $NIU, $SJSU, 'Little Caesars Pizza', 'ESPN', new \DateTime('2012-12-26 19:30:00'), 31.5, 50.5, 'Detroit, Mich.');
	    $this->createGame($manager, $ECU, $FRST, 'Sheraton Hawaii', 'ESPN', new \DateTime('2012-12-24 20:0:00'), 7, 50.5, 'Honolulu, Ha.');
	    $this->createGame($manager, $BOST, $AZST, 'MAACO Las Vegas', 'ESPN', new \DateTime('2012-12-22 15:30:00'), 2.5, 44.5, 'Las Vegas, Nev.');
	    $this->createGame($manager, $RICE, $ULL, 'R+L Carriers New Orleans', 'ESPN', new \DateTime('2012-12-22 12:0:00'), 4, 55.5, 'New Orleans, La.');
	    $this->createGame($manager, $PITT, $UCF, 'Beef \'O\' Brady\'s St. Petersburg', 'ESPN', new \DateTime('2012-12-21 19:30:00'), 2, 44.5, 'St. Petersburg, Fla.');
	    $this->createGame($manager, $BYU, $SDSU, 'S.D. County Credit Union Poinsettia', 'ESPN', new \DateTime('2012-12-20 20:0:00'), -7, 46, 'San Diego, Calif.');
	    $this->createGame($manager, $BAST, $UTST, 'Famous Idaho Potato', 'ESPN', new \DateTime('2012-12-15 16:30:00'), 26, 44.5, 'Boise, Idaho');
	    $this->createGame($manager, $NEV, $ARIZ, 'Gildan New Mexico', 'ESPN', new \DateTime('2012-12-15 13:0:00'), 2.5, 44, 'Albuquerque, N.M.');

        $manager->flush();

        // Leagues
        $pub1 = $this->createLeague($manager, 'College Crazies', 'dores', true,  array_merge($users, array($jsuggs, $granite777)), array($jsuggs, $commish1));

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

    private function createGame(ObjectManager $manager, Team $homeTeam, Team $awayTeam, $name, $shortName, $network, \DateTime $gameDate, $spread, $overUnder, $location)
    {
        $game = new Game();
        $game->setHomeTeam($homeTeam);
        $game->setAwayTeam($awayTeam);
        $game->setName($name);
        $game->setShortName($shortName);
        $game->setNetwork($network);
        $game->setGameDate($gameDate);
        $game->setSpread($spread);
        $game->setOverunder($overUnder);
        $game->setLocation($location);

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
}
