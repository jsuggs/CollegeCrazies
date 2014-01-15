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
        $this->addSql('CREATE INDEX IDX_SQUARES_GAME_PAYOUTS_WINNER_ID ON squares_game_payouts (winner_id)');
        $this->addSql('ALTER TABLE squares_game_payouts ADD CONSTRAINT FK_SQUARES_GAME_PAYOUTS_REF_SQUARES_PLAYERS_WINNER_ID FOREIGN KEY (winner_id) REFERENCES squares_players (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_USER_PROFILE_IMG_ID ON users (profile_img_id)');
        $this->addSql('ALTER TABLE predictions DROP CONSTRAINT fk_predictions_ref_prediction_sets');
        $this->addSql('ALTER TABLE predictions ADD CONSTRAINT FK_PREDICTIONS_REF_PREDICTION_SETS_PREDICTIONSET_ID FOREIGN KEY (predictionSet_id) REFERENCES prediction_sets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
    }
}
