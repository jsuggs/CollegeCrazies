<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20131127164615 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE leagues ADD season INT DEFAULT NULL");
        $this->addSql("UPDATE leagues SET season = 2012");
        $this->addSql("ALTER TABLE leagues ALTER season SET NOT NULL");
    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE leagues DROP season");
    }
}
