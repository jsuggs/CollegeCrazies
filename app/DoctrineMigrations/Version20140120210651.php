<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20140120210651 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE squares_players ADD admin BOOLEAN DEFAULT 'false' NOT NULL");
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE squares_players DROP admin');
    }
}
