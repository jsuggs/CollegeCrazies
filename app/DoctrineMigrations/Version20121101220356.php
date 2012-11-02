<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121101220356 extends AbstractMigration
{
    const CREATE_GAMES_SQL =<<<EOF
CREATE TABLE games (
    id INT NOT NULL
  , name VARCHAR(255) NOT NULL
  , gameDate TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
  , network VARCHAR(255) NOT NULL
  , homeTeamScore INT DEFAULT NULL
  , awayTeamScore INT DEFAULT NULL
  , description TEXT DEFAULT NULL
  , homeTeam_id VARCHAR(5) DEFAULT NULL
  , awayTeam_id VARCHAR(5) DEFAULT NULL
  , PRIMARY KEY(id)
);
EOF;

    public function up(Schema $schema)
    {
        $this->addSql('CREATE SEQUENCE seq_game INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(self::CREATE_GAMES_SQL);
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_GAMES_REF_TEAMS_HOMETEAM_ID FOREIGN KEY (homeTeam_id) REFERENCES teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_GAMES_REF_TEAMS_AWAYTEAM_ID FOREIGN KEY (awayTeam_id) REFERENCES teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_GAMES_HOMETEAM_ID ON games (homeTeam_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_GAMES_AWAYTEAM_ID ON games (awayTeam_id)');
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP SEQUENCE seq_game');
        $this->addSql('DROP TABLE games');
    }
}
