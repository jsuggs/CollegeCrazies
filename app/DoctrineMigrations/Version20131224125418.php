<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20131224125418 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('CREATE UNIQUE INDEX UNIQ_LEAGUE_LOGO_ID ON leagues (logo_id)');
        $this->addSql('DROP TABLE core_module_config');
        $this->addSql('DROP TABLE core_module');
    }

    public function down(Schema $schema)
    {
    }
}
