<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20131127204258 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE predictions DROP CONSTRAINT fk_predictions_ref_prediction_sets');
        $this->addSql('ALTER TABLE predictions ADD CONSTRAINT fk_predictions_ref_prediction_sets FOREIGN KEY (predictionset_id) REFERENCES prediction_sets (id) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE CASCADE');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE predictions DROP CONSTRAINT fk_predictions_ref_prediction_sets');
        $this->addSql('ALTER TABLE predictions ADD CONSTRAINT fk_predictions_ref_prediction_sets FOREIGN KEY (predictionset_id) REFERENCES prediction_sets (id) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
