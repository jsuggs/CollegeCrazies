<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20131127201458 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE prediction_sets ADD season INT DEFAULT NULL");
        $this->addSql("UPDATE prediction_sets SET season = 2012");
        $this->addSql("ALTER TABLE prediction_sets ALTER season SET NOT NULL");
    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE prediction_sets DROP season");
    }
}
