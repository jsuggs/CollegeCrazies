<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20131127183632 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE user_prediction_set_score ADD season INT DEFAULT NULL");
        $this->addSql("UPDATE user_prediction_set_score SET season = 2012");
        $this->addSql("ALTER TABLE user_prediction_set_score ALTER season SET NOT NULL");
        $this->addSql("ALTER TABLE user_prediction_set_score DROP CONSTRAINT user_prediction_set_score_pkey");
        $this->addSql("ALTER TABLE user_prediction_set_score ADD CONSTRAINT user_prediction_set_score_pkey PRIMARY KEY (user_id, league_id, season, predictionset_id, pickset_id )");
    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE user_prediction_set_score DROP CONSTRAINT user_prediction_set_score_pkey");
        $this->addSql("ALTER TABLE user_prediction_set_score DROP season");
        $this->addSql("ALTER TABLE user_prediction_set_score ADD CONSTRAINT user_prediction_set_score_pkey PRIMARY KEY (user_id, league_id, predictionset_id, pickset_id )");
    }
}
