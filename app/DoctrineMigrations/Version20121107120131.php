<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121107120131 extends AbstractMigration
{
    const CREATE_USER_PREDICTION_SET_SCORE =<<<EOF
CREATE TABLE user_prediction_set_score (
    user_id INT NOT NULL
  , predictionSet_id INT NOT NULL
  , pickSet_id INT NOT NULL
  , score INT NOT NULL
  , PRIMARY KEY(user_id, predictionSet_id, pickSet_id)
);
EOF;

    public function up(Schema $schema)
    {
        $this->addSql(self::CREATE_USER_PREDICTION_SET_SCORE);
        $this->addSql('CREATE INDEX IDX_USER_PREDICTION_SET_SCORE_USER_ID ON user_prediction_set_score (user_id)');
        $this->addSql('CREATE INDEX IDX_USER_PREDICTION_SET_SCORE_PREDEICTIONSET_ID ON user_prediction_set_score (predictionSet_id)');
        $this->addSql('CREATE INDEX IDX_USER_PREDICTION_SET_SCORE_PICKSET_ID ON user_prediction_set_score (pickSet_id)');
        $this->addSql('ALTER TABLE user_prediction_set_score ADD CONSTRAINT FK_EC56614BA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_prediction_set_score ADD CONSTRAINT FK_EC56614B5C4B5A62 FOREIGN KEY (predictionSet_id) REFERENCES prediction_sets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_prediction_set_score ADD CONSTRAINT FK_EC56614B9443CCDB FOREIGN KEY (pickSet_id) REFERENCES picksets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE user_prediction_set_score');
    }
}
