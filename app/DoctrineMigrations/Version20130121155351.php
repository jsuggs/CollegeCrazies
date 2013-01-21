<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20130121155351 extends AbstractMigration
{
    const CREATE_NLF_TEAM_SQL =<<<EOF
CREATE TABLE nfl_team (
    id VARCHAR(3) NOT NULL
  , name VARCHAR(255) NOT NULL
  , conference VARCHAR(3) NOT NULL
  , division VARCHAR(5) NOT NULL
  , PRIMARY KEY(id)
);
EOF;

    public function up(Schema $schema)
    {
        $this->addSql(self::CREATE_NLF_TEAM_SQL);

        // Add the NFL Teams
        $teams = array();

        // NFC East
        $teams[] = array(
            'id' => 'DAL',
            'name' => 'Dallas Cowboys',
            'conference' => 'NFC',
            'division' => 'East',
        );
        $teams[] = array(
            'id' => 'NYG',
            'name' => 'New York Giants',
            'conference' => 'NFC',
            'division' => 'East',
        );
        $teams[] = array(
            'id' => 'PHI',
            'name' => 'Philadelphia Eagles',
            'conference' => 'NFC',
            'division' => 'East',
        );
        $teams[] = array(
            'id' => 'WAS',
            'name' => 'Washington Redskins',
            'conference' => 'NFC',
            'division' => 'East',
        );

        // NFC South
        $teams[] = array(
            'id' => 'ATL',
            'name' => 'Atlanta Falcons',
            'conference' => 'NFC',
            'division' => 'South',
        );
        $teams[] = array(
            'id' => 'CAR',
            'name' => 'Carolina Panthers',
            'conference' => 'NFC',
            'division' => 'South',
        );
        $teams[] = array(
            'id' => 'NO',
            'name' => 'New Orleans Saints',
            'conference' => 'NFC',
            'division' => 'South',
        );
        $teams[] = array(
            'id' => 'TB',
            'name' => 'Tampa Bay Buccaneers',
            'conference' => 'NFC',
            'division' => 'South',
        );

        // NFC North
        $teams[] = array(
            'id' => 'CHI',
            'name' => 'Chicago Bears',
            'conference' => 'NFC',
            'division' => 'North',
        );
        $teams[] = array(
            'id' => 'DET',
            'name' => 'Detroit Lions',
            'conference' => 'NFC',
            'division' => 'North',
        );
        $teams[] = array(
            'id' => 'GB',
            'name' => 'Green Bay Packers',
            'conference' => 'NFC',
            'division' => 'North',
        );
        $teams[] = array(
            'id' => 'MIN',
            'name' => 'Minnesota Vikings',
            'conference' => 'NFC',
            'division' => 'North',
        );

        // NFC West
        $teams[] = array(
            'id' => 'ARI',
            'name' => 'Arizona Cardinals',
            'conference' => 'NFC',
            'division' => 'West',
        );
        $teams[] = array(
            'id' => 'SF',
            'name' => 'San Francisco 49ers',
            'conference' => 'NFC',
            'division' => 'West',
        );
        $teams[] = array(
            'id' => 'SEA',
            'name' => 'Seattle Seahawks',
            'conference' => 'NFC',
            'division' => 'West',
        );
        $teams[] = array(
            'id' => 'STL',
            'name' => 'St. Louis Rams',
            'conference' => 'NFC',
            'division' => 'West',
        );

        // AFC East
        $teams[] = array(
            'id' => 'BUF',
            'name' => 'Buffalo Bills',
            'conference' => 'AFC',
            'division' => 'East',
        );
        $teams[] = array(
            'id' => 'MIA',
            'name' => 'Miami Dolphins',
            'conference' => 'AFC',
            'division' => 'East',
        );
        $teams[] = array(
            'id' => 'NE',
            'name' => 'New England Patriots',
            'conference' => 'AFC',
            'division' => 'East',
        );
        $teams[] = array(
            'id' => 'NYJ',
            'name' => 'New York Jets',
            'conference' => 'AFC',
            'division' => 'East',
        );

        // AFC South
        $teams[] = array(
            'id' => 'HOU',
            'name' => 'Houston Texans',
            'conference' => 'AFC',
            'division' => 'South',
        );
        $teams[] = array(
            'id' => 'IND',
            'name' => 'Indianapolis Colts',
            'conference' => 'AFC',
            'division' => 'South',
        );
        $teams[] = array(
            'id' => 'JAX',
            'name' => 'Jacksonville Jaguars',
            'conference' => 'AFC',
            'division' => 'South',
        );
        $teams[] = array(
            'id' => 'TEN',
            'name' => 'Tennessee Titans',
            'conference' => 'AFC',
            'division' => 'South',
        );

        // AFC North
        $teams[] = array(
            'id' => 'BAL',
            'name' => 'Baltimore Ravens',
            'conference' => 'AFC',
            'division' => 'North',
        );
        $teams[] = array(
            'id' => 'CIN',
            'name' => 'Cincinnati Bengals',
            'conference' => 'AFC',
            'division' => 'North',
        );
        $teams[] = array(
            'id' => 'CLE',
            'name' => 'Cleveland Browns',
            'conference' => 'AFC',
            'division' => 'North',
        );
        $teams[] = array(
            'id' => 'PIT',
            'name' => 'Pittsburgh Steelers',
            'conference' => 'AFC',
            'division' => 'North',
        );

        // AFC West
        $teams[] = array(
            'id' => 'DEN',
            'name' => 'Denver Broncos',
            'conference' => 'AFC',
            'division' => 'West',
        );
        $teams[] = array(
            'id' => 'KC',
            'name' => 'Kansas City Chiefs',
            'conference' => 'AFC',
            'division' => 'West',
        );
        $teams[] = array(
            'id' => 'OAK',
            'name' => 'Oakland Raiders',
            'conference' => 'AFC',
            'division' => 'West',
        );
        $teams[] = array(
            'id' => 'SD',
            'name' => 'San Diego Chargers',
            'conference' => 'AFC',
            'division' => 'West',
        );

        foreach ($teams as $team) {
            $this->addSql(sprintf("INSERT INTO nfl_team (id, name, conference, division) values ('%s', '%s', '%s', '%s')", $team['id'], $team['name'], $team['conference'], $team['division']));
        }
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE nfl_team');
    }
}
