<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121108155248 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE user_prediction_set_score ADD league_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_prediction_set_score DROP CONSTRAINT user_prediction_set_score_pkey');
        $this->addSql('ALTER TABLE user_prediction_set_score ADD CONSTRAINT FK_USER_PREDICTION_SET_SCORE_REF_LEAGUES_LEAGUE_ID FOREIGN KEY (league_id) REFERENCES leagues (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_USER_PREDICTION_SET_SCORE_LEAGUE_ID ON user_prediction_set_score (league_id)');
        $this->addSql('ALTER TABLE user_prediction_set_score ADD PRIMARY KEY (user_id, league_id, predictionSet_id, pickSet_id)');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE user_prediction_set_score DROP CONSTRAINT user_prediction_set_score_pkey');
        $this->addSql('ALTER TABLE user_prediction_set_score DROP league_id');
        $this->addSql('ALTER TABLE user_prediction_set_score ADD PRIMARY KEY (user_id, predictionSet_id, pickSet_id)');
    }
}
