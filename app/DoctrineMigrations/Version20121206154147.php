<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121206154147 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE leagues DROP public');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE leagues ADD public BOOLEAN NOT NULL DEFAULT TRUE');
    }
}
