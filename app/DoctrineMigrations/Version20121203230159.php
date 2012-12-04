<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121203230159 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE teams ADD thumbnail VARCHAR(255) NOT NULL DEFAULT \'\'');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE teams DROP thumbnail');
    }
}
