<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121126235245 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE leagues ADD locked BOOLEAN NOT NULL DEFAULT false');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE leagues DROP locked');
    }
}
