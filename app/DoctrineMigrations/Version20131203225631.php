<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20131203225631 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE picksets ADD season INT DEFAULT NULL");
        $this->addSql("UPDATE picksets SET season = 2012");
        $this->addSql("ALTER TABLE picksets ALTER season SET NOT NULL");
    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE picksets DROP season");
    }
}
