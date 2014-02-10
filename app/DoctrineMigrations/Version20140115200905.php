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
        $this->addSql(self::NCAA_TEAMS_SQL);
        $this->addSql(self::MIGRATE_TEAMS_UP);
        $this->addSql(self::NCAAF_CONFERENCE_MEMBERS);
        $this->addSql("CREATE INDEX IDX_NCAAF_CONFERENCE_MEMBERS_CONFERENCE ON ncaaf_conference_members (conference)");
        $this->addSql("CREATE INDEX IDX_NCAAF_CONFERENCE_MEMBERS_TEAM ON ncaaf_conference_members (team)");
        $this->addSql(self::NCAA_CONFERENCES);
        $this->addSql("ALTER TABLE ncaaf_conference_members ADD CONSTRAINT FK_NCAA_CONFERENCE_MEMBERS_REF_NCAA_CONFERENCES_CONFERENCE FOREIGN KEY (conference) REFERENCES ncaa_conferences (abbr) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE ncaaf_conference_members ADD CONSTRAINT FK_NCAA_CONFERENCE_MEMBERS_REF_NCAA_TEAMS_TEAM FOREIGN KEY (team) REFERENCES ncaa_teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql(self::NCAA_CONFERENCE_DIVISION);
        $this->addSql('ALTER TABLE bp_expert_picks ADD CONSTRAINT FK_BP_EXPERT_PICKS_REF_NCAA_TEAMS_ID FOREIGN KEY (team_id) REFERENCES ncaa_teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_GAMES_REF_NCAA_TEAMS_HOMETEAM_ID FOREIGN KEY (homeTeam_id) REFERENCES ncaa_teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_GAMES_REF_NCAA_TEAMS_AWAYTEAM_ID FOREIGN KEY (awayTeam_id) REFERENCES ncaa_teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE picks ADD CONSTRAINT FK_PICKS_REF_NCAA_TEAMS_TEAM_ID FOREIGN KEY (team_id) REFERENCES ncaa_teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE predictions ADD CONSTRAINT FK_PREDICTIONS_REF_NCAA_TEAMS_WINNER_ID FOREIGN KEY (winner_id) REFERENCES ncaa_teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mm_games ADD CONSTRAINT FK_MM_GAMES_REF_MM_BRACKETS_SEASON FOREIGN KEY (season) REFERENCES mm_brackets (season) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_MM_GAMES_SEASON ON mm_games (season)');
        $this->addSql('ALTER TABLE ncaa_conference_divisions ADD CONSTRAINT FK_NCAA_CONFERENCE_DIVISIONS_REF_NCAA_CONFERENCES_CONFERENCE FOREIGN KEY (conference) REFERENCES ncaa_conferences (abbr) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ncaaf_conference_members ADD CONSTRAINT FK_NCAA_CONFERENCE_MEMBERS_REF_NCAA_CONFERENCE_DIVISIONS_DIVISION FOREIGN KEY (division) REFERENCES ncaa_conference_divisions (abbr) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_NCAAF_CONFERENCE_MEMBERS ON ncaaf_conference_members (division)');
        $this->addSql('CREATE INDEX IDX_MM_TEAMS_SEASON ON mm_teams (season)');
        $this->addSql('CREATE INDEX IDX_MM_TEAMS_SEASON_REGION ON mm_teams (season, region)');
        $this->addSql('ALTER TABLE mm_teams ADD CONSTRAINT FK_MM_TEAMS_REF_MM_BRACKETS_SEASON FOREIGN KEY (season) REFERENCES mm_brackets (season) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mm_teams ADD CONSTRAINT FK_MM_TEAMS_REF_NCAA_TEAMS_TEAM_ID FOREIGN KEY (team_id) REFERENCES ncaa_teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mm_games ADD CONSTRAINT FK_MM_GAMES_REF_MM_TEAMS_SEASON_HOMETEAM_ID FOREIGN KEY (season, hometeam_id) REFERENCES mm_teams (season, team_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mm_games ADD CONSTRAINT FK_MM_GAMES_REF_MM_TEAMS_SEASON_AWAYTEAM_ID FOREIGN KEY (season, awayteam_id) REFERENCES mm_teams (season, team_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_MM_GAMES_SEASON_HOMETEAM_ID ON mm_games (season, hometeam_id)');
        $this->addSql('CREATE INDEX IDX_MM_GAMES_SEASON_AWAYTEAM_ID ON mm_games (season, awayteam_id)');
        $this->addSql(self::MM_REGIONS);
        $this->addSql('CREATE INDEX IDX_MM_REGIONS_SEASON ON mm_regions (season)');
        $this->addSql('ALTER TABLE mm_regions ADD CONSTRAINT FK_MM_REGIONS_REF_MM_BRACKETS_SEASON FOREIGN KEY (season) REFERENCES mm_brackets (season) DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mm_games ADD CONSTRAINT FK_MM_GAMES_REF_MM_REGIONS_SEASON_REGION FOREIGN KEY (season, region) REFERENCES mm_regions (season, abbr) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mm_teams ADD CONSTRAINT FK_MM_TEAMS_REF_MM_REGIONS_SEASON_REGION FOREIGN KEY (season, region) REFERENCES mm_regions (season, abbr) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql("COMMENT ON COLUMN users.roles IS '(DC2Type:array)'");
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
