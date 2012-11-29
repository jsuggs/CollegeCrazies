<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121129131119 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE leagues ALTER password DROP NOT NULL;');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE leagues ALTER password SET NOT NULL;');
    }
}
