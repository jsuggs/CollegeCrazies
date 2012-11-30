<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121129231236 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE games ADD location VARCHAR(255) DEFAULT NULL');
        $this->addSql('UPDATE games SET location = \'\'');
        $this->addSql('ALTER TABLE games ALTER location SET NOT NULL');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE games DROP location');
    }
}
