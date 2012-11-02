<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121101215556 extends AbstractMigration
{
    const CREATE_PICKSETS_SQL =<<<EOF
CREATE TABLE picksets (
    id INT NOT NULL
  , league_id INT DEFAULT NULL
  , user_id INT DEFAULT NULL
  , name VARCHAR(255) NOT NULL
  , tiebreakerHomeTeamScore INT DEFAULT NULL
  , tiebreakerAwayTeamScore INT DEFAULT NULL
  , PRIMARY KEY(id)
);
EOF;

    public function up(Schema $schema)
    {
        $this->addSql('CREATE SEQUENCE seq_pickset INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(self::CREATE_PICKSETS_SQL);
        $this->addSql('ALTER TABLE picksets ADD CONSTRAINT FK_PICKSETS_REF_LEAGUES_LEAGUE_ID FOREIGN KEY (league_id) REFERENCES leagues (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE picksets ADD CONSTRAINT FK_PICKSETS_REF_USERS_USER_ID FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_PICKSETS_LEAGUE_ID ON picksets (league_id)');
        $this->addSql('CREATE INDEX IDX_PICKSETS_USER_ID ON picksets (user_id)');
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP SEQUENCE seq_pickset');
        $this->addSql('DROP TABLE pickets');
    }
}
