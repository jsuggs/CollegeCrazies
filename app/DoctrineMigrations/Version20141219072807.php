<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20141219072807 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE games ADD championshipGame BOOLEAN NOT NULL DEFAULT false');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE games DROP championshipGame');
    }
}
