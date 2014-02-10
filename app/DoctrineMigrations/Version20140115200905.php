<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20140115200905 extends AbstractMigration
{
    const CREATE_MM_GAMES_SQL =<<<EOF
CREATE TABLE mm_games (
    id VARCHAR(16) NOT NULL
  , season SMALLINT NOT NULL
  , region VARCHAR(8) DEFAULT NULL
  , hometeam_id VARCHAR(5) DEFAULT NULL
  , awayteam_id VARCHAR(5) DEFAULT NULL
  , name VARCHAR(32) NOT NULL
  , round SMALLINT NOT NULL
  , index SMALLINT NOT NULL
  , homeTeamScore INT DEFAULT NULL
  , awayTeamScore INT DEFAULT NULL
  , gameDate TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
  , location VARCHAR(255) NOT NULL
  , PRIMARY KEY(id)
);
EOF;

    const CREATE_MM_BRACKETS_SQL =<<<EOF
CREATE TABLE mm_brackets (
    season SMALLINT NOT NULL
  , PRIMARY KEY(season)
);
EOF;

    const MM_TEAMS =<<<SQL
CREATE TABLE mm_teams (
    team_id VARCHAR(5) NOT NULL
  , season SMALLINT DEFAULT NULL
  , region VARCHAR(8) DEFAULT NULL
  , overallSeed SMALLINT NOT NULL
  , regionSeed SMALLINT NOT NULL
  , PRIMARY KEY(season, team_id)
)
SQL;

    const MM_REGIONS =<<<SQL
CREATE TABLE mm_regions (
    season SMALLINT NOT NULL
  , abbr VARCHAR(8) NOT NULL
  , name VARCHAR(32) NOT NULL
  , index SMALLINT NOT NULL
  , PRIMARY KEY(season, abbr)
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
  , season SMALLINT NOT NULL
  , division VARCHAR(5) DEFAULT NULL
  , PRIMARY KEY(conference, team, season)
)
SQL;

    const NCAA_CONFERENCE_DIVISION =<<<SQL
CREATE TABLE ncaa_conference_divisions (
    abbr VARCHAR(5) NOT NULL
  , conference VARCHAR(5) DEFAULT NULL
  , name VARCHAR(255) NOT NULL
  , PRIMARY KEY(abbr))
SQL;

    public function up(Schema $schema)
    {
        $this->addSql('CREATE SEQUENCE seq_mm_games INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE seq_mm_brackets INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE seq_mm_user_brackets INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(self::CREATE_MM_GAMES_SQL);
        $this->addSql(self::CREATE_MM_BRACKETS_SQL);
        $this->addSql(self::MM_TEAMS);
        $this->addSql('CREATE INDEX IDX_MM_USER_BRACKETS_USER_ID ON mm_user_brackets (user_id)');
        $this->addSql('ALTER TABLE mm_user_brackets ADD CONSTRAINT FK_4DA3E3FFA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql(self::NCAA_TEAMS_SQL);
        $this->addSql(self::MIGRATE_TEAMS_UP);
        $this->addSql(self::NCAAF_CONFERENCE_MEMBERS);
        $this->addSql("CREATE INDEX IDX_NCAAF_CONFERENCE_MEMBERS_CONFERENCE ON ncaaf_conference_members (conference)");
        $this->addSql("CREATE INDEX IDX_NCAAF_CONFERENCE_MEMBERS_TEAM ON ncaaf_conference_members (team)");
        $this->addSql(self::NCAA_CONFERENCES);
        $this->addSql("ALTER TABLE ncaaf_conference_members ADD CONSTRAINT FK_NCAA_CONFERENCE_MEMBERS_REF_NCAA_CONFERENCES_CONFERENCE FOREIGN KEY (conference) REFERENCES ncaa_conferences (abbr) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE ncaaf_conference_members ADD CONSTRAINT FK_NCAA_CONFERENCE_MEMBERS_REF_NCAA_TEAMS_TEAM FOREIGN KEY (team) REFERENCES ncaa_teams (id) ON DELETE CASCADE");
        $this->addSql('ALTER TABLE ncaaf_conference_members ADD CONSTRAINT FK_AE8F1CC9911533C8 FOREIGN KEY (conference) REFERENCES ncaa_conferences (abbr) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql(self::NCAA_CONFERENCE_DIVISION);
        $this->addSql('ALTER TABLE bp_expert_picks ADD CONSTRAINT FK_5CB1286B296CD8AE FOREIGN KEY (team_id) REFERENCES ncaa_teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_FF232B31EFE66F0C FOREIGN KEY (homeTeam_id) REFERENCES ncaa_teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_FF232B316DF247E5 FOREIGN KEY (awayTeam_id) REFERENCES ncaa_teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE picks ADD CONSTRAINT FK_7C4A48C8296CD8AE FOREIGN KEY (team_id) REFERENCES ncaa_teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE predictions ADD CONSTRAINT FK_8E87BCE65DFCD4B8 FOREIGN KEY (winner_id) REFERENCES ncaa_teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mm_games ADD CONSTRAINT FK_742128F0E45BA9 FOREIGN KEY (season) REFERENCES mm_brackets (season) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_742128F0E45BA9 ON mm_games (season)');
        $this->addSql('ALTER TABLE ncaa_conference_divisions ADD CONSTRAINT FK_5D00B5F3911533C8 FOREIGN KEY (conference) REFERENCES ncaa_conferences (abbr) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ncaaf_conference_members ADD CONSTRAINT FK_AE8F1CC9C4E0A61F FOREIGN KEY (team) REFERENCES ncaa_teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ncaaf_conference_members ADD CONSTRAINT FK_AE8F1CC910174714 FOREIGN KEY (division) REFERENCES ncaa_conference_divisions (abbr) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_AE8F1CC910174714 ON ncaaf_conference_members (division)');
        $this->addSql('CREATE INDEX IDX_69952841F0E45BA9 ON mm_teams (season)');
        $this->addSql('CREATE INDEX IDX_69952841F0E45BA9F62F176 ON mm_teams (season, region)');
        $this->addSql('ALTER TABLE mm_teams ADD CONSTRAINT FK_69952841F0E45BA9 FOREIGN KEY (season) REFERENCES mm_brackets (season) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mm_teams ADD CONSTRAINT FK_69952841296CD8AE FOREIGN KEY (team_id) REFERENCES ncaa_teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mm_games ADD CONSTRAINT FK_742128F0E45BA9205B5690 FOREIGN KEY (season, hometeam_id) REFERENCES mm_teams (season, team_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mm_games ADD CONSTRAINT FK_742128F0E45BA9A24F7E79 FOREIGN KEY (season, awayteam_id) REFERENCES mm_teams (season, team_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_MM_GAMES_SEASON_HOMETEAM_ID ON mm_games (season, hometeam_id)');
        $this->addSql('CREATE INDEX IDX_MM_GAMES_SEASON_AWAYTEAM_ID ON mm_games (season, awayteam_id)');
        $this->addSql(self::MM_REGIONS);
        $this->addSql('CREATE INDEX IDX_D9B2C6A2F0E45BA9 ON mm_regions (season)');
        $this->addSql('ALTER TABLE mm_regions ADD CONSTRAINT FK_D9B2C6A2F0E45BA9 FOREIGN KEY (season) REFERENCES mm_brackets (season) DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mm_games ADD CONSTRAINT FK_742128F0E45BA94D4901 FOREIGN KEY (season, region) REFERENCES mm_regions (season, abbr) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mm_teams ADD CONSTRAINT FK_69952841F0E45BA9F62F176 FOREIGN KEY (season, region) REFERENCES mm_regions (season, abbr) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        $this->addSql(self::MIGRATE_TEAMS_DOWN);
        $this->addSql('DROP SEQUENCE seq_mm_games');
        $this->addSql('DROP SEQUENCE seq_mm_brackets');
        $this->addSql('DROP SEQUENCE seq_mm_user_brackets');
        $this->addSql('DROP TABLE mm_games');
        $this->addSql('DROP TABLE mm_brackets');
        $this->addSql("DROP TABLE mm_teams");
        $this->addSql('DROP TABLE ncaa_teams');
        $this->addSql('DROP TABLE ncaa_conference_divisions');
        $this->addSql("DROP TABLE ncaaf_conference_members");
        $this->addSql("DROP TABLE ncaa_conferences");
    }
}
