<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121211212416 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE users ADD emailFromCommish BOOLEAN DEFAULT false NOT NULL');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE users DROP emailFromCommish');
    }
}
