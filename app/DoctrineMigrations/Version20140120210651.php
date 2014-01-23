<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20140120210651 extends AbstractMigration
{
    const SQUARES_LOG =<<<SQL
CREATE TABLE squares_log (
    id INT NOT NULL
  , game_id INT DEFAULT NULL
  , message VARCHAR(255) NOT NULL
  , createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
  , PRIMARY KEY(id)
)
SQL;
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE squares_players ADD admin BOOLEAN DEFAULT 'false' NOT NULL");
        $this->addSql(self::SQUARES_LOG);
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE squares_players DROP admin');
        $this->addSql('DROP TABLE squares_log');
    }
}
