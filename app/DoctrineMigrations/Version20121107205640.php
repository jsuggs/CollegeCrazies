<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121107205640 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE user_prediction_set_score ADD finish INT DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE user_prediction_set_score DROP finish');
    }
}
