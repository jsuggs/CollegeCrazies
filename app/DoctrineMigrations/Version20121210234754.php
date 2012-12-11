<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121210234754 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE leagues DROP locktime');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE leagues ADD lockTime TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
    }
}
