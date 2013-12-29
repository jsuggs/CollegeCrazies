<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20131215204839 extends AbstractMigration
{
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
        $this->addSql('DROP TABLE ncaa_conference_divisions');
        $this->addSql("ALTER TABLE ncaaf_conference_members DROP CONSTRAINT fk_ncaa_conference_members_ref_ncaa_conferences_conference");
        $this->addSql("DROP TABLE ncaaf_conference_members");
        $this->addSql("DROP TABLE ncaa_conferences");
    }
}
