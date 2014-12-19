<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20141219122558 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE prediction_sets ADD championshipWinner_id VARCHAR(5) DEFAULT NULL');
        $this->addSql('ALTER TABLE prediction_sets ADD CONSTRAINT FK_D12E3EEF5F2860E0 FOREIGN KEY (championshipWinner_id) REFERENCES ncaa_teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D12E3EEF5F2860E0 ON prediction_sets (championshipWinner_id)');

        // Cleanup
        $this->addSql('ALTER TABLE games ALTER season DROP NOT NULL');
        $this->addSql('ALTER TABLE prediction_sets ADD CONSTRAINT FK_D12E3EEFF0E45BA9 FOREIGN KEY (season) REFERENCES bp_seasons (season) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bp_seasons ALTER locked DROP DEFAULT');
        $this->addSql('ALTER TABLE prediction_sets ALTER season DROP NOT NULL');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_FF232B31F0E45BA9 FOREIGN KEY (season) REFERENCES bp_seasons (season) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_FF232B31F0E45BA9 ON games (season)');
        $this->addSql('ALTER TABLE leagues ALTER season DROP NOT NULL');
        $this->addSql('ALTER TABLE leagues ADD CONSTRAINT FK_85CE39EF0E45BA9 FOREIGN KEY (season) REFERENCES bp_seasons (season) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_85CE39EF0E45BA9 ON leagues (season)');
        $this->addSql('ALTER TABLE user_prediction_set_score ADD CONSTRAINT FK_EC56614BF0E45BA9 FOREIGN KEY (season) REFERENCES bp_seasons (season) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_EC56614BF0E45BA9 ON user_prediction_set_score (season)');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE prediction_sets DROP championshipWinner_id');
    }
}
