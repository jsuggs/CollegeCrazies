<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20131223213301 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE users ADD timezone VARCHAR(30) DEFAULT 'America/Chicago' NOT NULL");
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE users DROP timezone');
    }
}
