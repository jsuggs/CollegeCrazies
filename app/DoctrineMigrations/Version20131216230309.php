<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20131216230309 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('CREATE INDEX IDX_PREDICTION_SETS_SEASON ON prediction_sets (season)');
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP INDEX IDX_PREDICTION_SETS_SEASON');
    }
}
