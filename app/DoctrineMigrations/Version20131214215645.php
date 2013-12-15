<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20131214215645 extends AbstractMigration
{
    const NCAA_TEAMS_SQL =<<<SQL
CREATE TABLE ncaa_teams (
    id VARCHAR(5) NOT NULL
  , thumbnail VARCHAR(255) NOT NULL
  , name VARCHAR(255) NOT NULL
  , type VARCHAR(255) NOT NULL
  , PRIMARY KEY(id)
)
SQL;

    const MIGRATE_TEAMS_UP =<<<SQL
INSERT INTO ncaa_teams
SELECT
    id
  , thumbnail
  , name
  , 'bp'
FROM teams
SQL;

    const MIGRATE_TEAMS_DOWN =<<<SQL
INSERT INTO teams
SELECT
    id
  , thumbnail
  , name
FROM ncaa_teams
SQL;

    public function up(Schema $schema)
    {
        $this->addSql(self::NCAA_TEAMS_SQL);
        $this->addSql(self::MIGRATE_TEAMS_UP);
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE ncaa_teams');
    }
}
