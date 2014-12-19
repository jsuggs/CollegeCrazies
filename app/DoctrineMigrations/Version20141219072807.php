<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20141219072807 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE games ADD playoffGame BOOLEAN NOT NULL DEFAULT false');
        $this->addSql('ALTER TABLE games ALTER playoffGame DROP DEFAULT');
        $this->addSql('UPDATE games set playoffGame = true where id IN(101, 102)');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE games DROP playoffGame');
    }
}
