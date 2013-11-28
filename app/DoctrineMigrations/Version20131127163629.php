<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20131127163629 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE games ADD season INT DEFAULT NULL");
        $this->addSql("UPDATE games SET season = 2012");
        $this->addSql("ALTER TABLE games ALTER season SET NOT NULL");
    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE games DROP season");
    }
}
