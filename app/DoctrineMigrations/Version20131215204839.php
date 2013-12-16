<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20131215204839 extends AbstractMigration
{
    const NCAA_CONFERENCE_MEMBERS =<<<SQL
CREATE TABLE ncaa_conferences (
    abbr VARCHAR(5) NOT NULL
  , name VARCHAR(255) NOT NULL
  , type VARCHAR(255) NOT NULL
  , PRIMARY KEY(abbr)
)
SQL;

    public function up(Schema $schema)
    {
        $this->addSql("CREATE TABLE ncaa_conference_members (conference VARCHAR(5) NOT NULL, team VARCHAR(5) NOT NULL, season INT NOT NULL, PRIMARY KEY(conference, team, season))");
        $this->addSql("CREATE INDEX IDX_9156FFFE911533C8 ON ncaa_conference_members (conference)");
        $this->addSql("CREATE INDEX IDX_9156FFFEC4E0A61F ON ncaa_conference_members (team)");
        $this->addSql(self::NCAA_CONFERENCE_MEMBERS);
        $this->addSql("ALTER TABLE ncaa_conference_members ADD CONSTRAINT FK_9156FFFE911533C8 FOREIGN KEY (conference) REFERENCES ncaa_conferences (abbr) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE ncaa_conference_members ADD CONSTRAINT FK_9156FFFEC4E0A61F FOREIGN KEY (team) REFERENCES ncaa_teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE ncaa_conference_members DROP CONSTRAINT FK_9156FFFE911533C8");
        $this->addSql("DROP TABLE ncaa_conference_members");
        $this->addSql("DROP TABLE ncaa_conferences");
    }
}
