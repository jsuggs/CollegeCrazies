<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121101220004 extends AbstractMigration
{
    const CREATE_TEAMS_SQL =<<<EOF
CREATE TABLE teams (
    id VARCHAR(5) NOT NULL
  , name VARCHAR(255) NOT NULL
  , PRIMARY KEY(id)
);
EOF;

    public function up(Schema $schema)
    {
        $this->addSql(self::CREATE_TEAMS_SQL);
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE teams');
    }
}
