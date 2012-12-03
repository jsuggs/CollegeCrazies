<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121202211148 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE games ADD shortName VARCHAR(12) DEFAULT \'\' NOT NULL');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE games DROP shortName');
    }
}
