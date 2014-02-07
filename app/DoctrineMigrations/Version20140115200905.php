<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20140115200905 extends AbstractMigration
{
    const CREATE_MM_GAMES_SQL =<<<EOF
CREATE TABLE mm_games (
    id INT NOT NULL
  , season INT DEFAULT NULL
  , parent_id INT DEFAULT NULL
  , homeTeamScore INT DEFAULT NULL
  , awayTeamScore INT DEFAULT NULL
  , gameDate TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
  , location VARCHAR(255) NOT NULL
  , PRIMARY KEY(id)
);
EOF;

    const CREATE_MM_BRACKETS_SQL =<<<EOF
CREATE TABLE mm_brackets (
    season INT NOT NULL
  , PRIMARY KEY(season)
);
EOF;
    const CREATE_MM_USER_BRACKETS_SQL =<<<SQL
CREATE TABLE mm_user_brackets (
    id INT NOT NULL
  , user_id INT DEFAULT NULL
  , name VARCHAR(255) NOT NULL
  , PRIMARY KEY(id)
)
SQL;
    const CREATE_MM_PICKS_SQL =<<<SQL
CREATE TABLE mm_picks (
    id INT NOT NULL
  , bracket_id INT DEFAULT NULL
  , game_id INT DEFAULT NULL
  , name VARCHAR(255) NOT NULL
  , homeTeamScore INT DEFAULT NULL
  , awayTeamScore INT DEFAULT NULL
  , gameDate TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
  , location VARCHAR(255) NOT NULL
  , PRIMARY KEY(id)
)
SQL;

    const NCAA_TEAMS_SQL =<<<SQL
CREATE TABLE ncaa_teams (
    id VARCHAR(5) NOT NULL
  , name VARCHAR(255) NOT NULL
  , thumbnail VARCHAR(255) NOT NULL
  , PRIMARY KEY(id)
)
SQL;

    const MIGRATE_TEAMS_UP =<<<SQL
INSERT INTO ncaa_teams
SELECT
    id
  , name
  , thumbnail
FROM teams
SQL;

    const MIGRATE_TEAMS_DOWN =<<<SQL
INSERT INTO teams
SELECT
    id
  , name
  , thumbnail
FROM ncaa_teams
SQL;

    const NCAA_CONFERENCES =<<<SQL
CREATE TABLE ncaa_conferences (
    abbr VARCHAR(5) NOT NULL
  , name VARCHAR(255) NOT NULL
  , PRIMARY KEY(abbr)
)
SQL;

    const NCAAF_CONFERENCE_MEMBERS =<<<SQL
CREATE TABLE ncaaf_conference_members (
    conference VARCHAR(5) NOT NULL
  , team VARCHAR(5) NOT NULL
  , season INT NOT NULL
  , division VARCHAR(5) DEFAULT NULL
  , PRIMARY KEY(conference, team, season)
)
SQL;

    public function up(Schema $schema)
    {
        $this->addSql('CREATE SEQUENCE seq_mm_games INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE seq_mm_brackets INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE seq_mm_user_brackets INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(self::CREATE_MM_GAMES_SQL);
        $this->addSql(self::CREATE_MM_BRACKETS_SQL);
        $this->addSql(self::CREATE_MM_USER_BRACKETS_SQL);
        $this->addSql(self::CREATE_MM_PICKS_SQL);
        $this->addSql('CREATE INDEX IDX_MM_USER_BRACKETS_USER_ID ON mm_user_brackets (user_id)');
        $this->addSql('CREATE INDEX IDX_MM_PICKST_BRACKET_ID ON mm_picks (bracket_id)');
        $this->addSql('CREATE INDEX IDX_MM_PICKS_GAME_ID ON mm_picks (game_id)');
        $this->addSql('ALTER TABLE mm_user_brackets ADD CONSTRAINT FK_4DA3E3FFA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mm_picks ADD CONSTRAINT FK_831D42D16E8D78 FOREIGN KEY (bracket_id) REFERENCES mm_user_brackets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql(self::NCAA_TEAMS_SQL);
        $this->addSql(self::MIGRATE_TEAMS_UP);
        $this->addSql(self::NCAAF_CONFERENCE_MEMBERS);
        $this->addSql("CREATE INDEX IDX_NCAAF_CONFERENCE_MEMBERS_CONFERENCE ON ncaaf_conference_members (conference)");
        $this->addSql("CREATE INDEX IDX_NCAAF_CONFERENCE_MEMBERS_TEAM ON ncaaf_conference_members (team)");
        $this->addSql(self::NCAA_CONFERENCES);
        $this->addSql("ALTER TABLE ncaaf_conference_members ADD CONSTRAINT FK_NCAA_CONFERENCE_MEMBERS_REF_NCAA_CONFERENCES_CONFERENCE FOREIGN KEY (conference) REFERENCES ncaa_conferences (abbr) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE ncaaf_conference_members ADD CONSTRAINT FK_NCAA_CONFERENCE_MEMBERS_REF_NCAA_TEAMS_TEAM FOREIGN KEY (team) REFERENCES ncaa_teams (id) ON DELETE CASCADE");
        $this->addSql('CREATE TABLE ncaa_conference_divisions (abbr VARCHAR(5) NOT NULL, conference VARCHAR(5) DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(abbr))');
        $this->addSql('CREATE INDEX IDX_5D00B5F3911533C8 ON ncaa_conference_divisions (conference)');
        $this->addSql('ALTER TABLE ncaa_conference_divisions ADD CONSTRAINT FK_5D00B5F3911533C8 FOREIGN KEY (conference) REFERENCES ncaa_conferences (abbr) ON DELETE CASCADE');
    }

    public function down(Schema $schema)
    {
        $this->addSql(self::MIGRATE_TEAMS_DOWN);
        $this->addSql('DROP SEQUENCE seq_mm_games');
        $this->addSql('DROP SEQUENCE seq_mm_brackets');
        $this->addSql('DROP SEQUENCE seq_mm_user_brackets');
        $this->addSql('DROP TABLE mm_games');
        $this->addSql('DROP TABLE mm_brackets');
        $this->addSql('DROP TABLE mm_user_brackets');
        $this->addSql('DROP TABLE ncaa_teams');
        $this->addSql('DROP TABLE ncaa_conference_divisions');
        $this->addSql("DROP TABLE ncaaf_conference_members");
        $this->addSql("DROP TABLE ncaa_conferences");
    }
}