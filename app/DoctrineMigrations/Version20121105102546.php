<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121105102546 extends AbstractMigration
{
    const CREATE_PREDICTION_SETS_SQL =<<<EOF
CREATE TABLE prediction_sets (
    id INT NOT NULL
  , PRIMARY KEY(id)
);
EOF;

    const CREATE_PREDICTIONS_SQL =<<<EOF
CREATE TABLE predictions (
    predictionSet_id INT NOT NULL
  , game_id INT DEFAULT NULL
  , winner_id VARCHAR(5) DEFAULT NULL
  , homeTeamScore INT NOT NULL
  , awayTeamScore INT NOT NULL
  , PRIMARY KEY(predictionSet_id, game_id)
);
EOF;

    public function up(Schema $schema)
    {
        $this->addSql('CREATE SEQUENCE seq_prediction_set INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(self::CREATE_PREDICTION_SETS_SQL);

        $this->addSql('CREATE SEQUENCE seq_prediction INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(self::CREATE_PREDICTIONS_SQL);
        $this->addSql('CREATE INDEX IDX_PREDICTIONS_GAME_ID ON predictions (game_id)');
        $this->addSql('CREATE INDEX IDX_PREDICTIONS_WINNER_ID ON predictions (winner_id)');
        $this->addSql('CREATE INDEX IDX_PREDICTION_SETS_PREDICTIONSET_ID ON predictions (predictionSet_id)');
        $this->addSql('ALTER TABLE predictions ADD CONSTRAINT FK_PREDICTIONS_REF_GAMES_GAME_ID FOREIGN KEY (game_id) REFERENCES games (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE predictions ADD CONSTRAINT FK_PREDICTIONS_REF_TEAMS_WINNER_ID FOREIGN KEY (winner_id) REFERENCES teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE predictions ADD CONSTRAINT FK_PREDICTIONS_REF_PREDICTION_SETS FOREIGN KEY (predictionSet_id) REFERENCES prediction_sets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP SEQUENCE seq_prediction');
        $this->addSql('DROP TABLE predictions');

        $this->addSql('DROP SEQUENCE seq_prediction_set');
        $this->addSql('DROP TABLE prediction_sets');
    }
}
