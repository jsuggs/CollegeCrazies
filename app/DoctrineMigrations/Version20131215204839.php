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
  , type VARCHAR(255) NOT NULL
  , PRIMARY KEY(abbr)
)
SQL;

    const NCAA_CONFERENCE_MEMBERS =<<<SQL
CREATE TABLE ncaa_conference_members (
    conference VARCHAR(5) NOT NULL
  , team VARCHAR(5) NOT NULL
  , season INT NOT NULL
  , PRIMARY KEY(conference, team, season)
)
SQL;

    public function up(Schema $schema)
    {
        $this->addSql(self::NCAA_CONFERENCE_MEMBERS);
        $this->addSql("CREATE INDEX IDX_9156FFFE911533C8 ON ncaa_conference_members (conference)");
        $this->addSql("CREATE INDEX IDX_9156FFFEC4E0A61F ON ncaa_conference_members (team)");
        $this->addSql(self::NCAA_CONFERENCES);
        $this->addSql("ALTER TABLE ncaa_conference_members ADD CONSTRAINT FK_NCAA_CONFERENCE_MEMBERS_REF_NCAA_CONFERENCES_CONFERENCE FOREIGN KEY (conference) REFERENCES ncaa_conferences (abbr) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE ncaa_conference_members ADD CONSTRAINT FK_NCAA_CONFERENCE_MEMBERS_REF_NCAA_TEAMS_TEAM FOREIGN KEY (team) REFERENCES ncaa_teams (id) ON DELETE CASCADE");
    }

    public function down(Schema $schema)
    {
        $this->addSql("DROP TABLE ncaa_conference_members");
        $this->addSql("DROP TABLE ncaa_conferences");
    }
}
