<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20130202200905 extends AbstractMigration
{
    const CREATE_MM_GAMES_SQL =<<<EOF
CREATE TABLE mm_games (
    id INT NOT NULL
  , season INT DEFAULT NULL
  , parent_id INT DEFAULT NULL
  , homeTeamScore INT DEFAULT NULL
  , awayTeamScore INT DEFAULT NULL
  , gameDate TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
  , location VARCHAR(255) NOT NULL
  , PRIMARY KEY(id)
);
EOF;

    const CREATE_MM_BRACKETS_SQL =<<<EOF
CREATE TABLE mm_brackets (
    season INT NOT NULL
  , PRIMARY KEY(season)
);
EOF;

    public function up(Schema $schema)
    {
        $this->addSql('CREATE SEQUENCE seq_mm_games INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE seq_mm_brackets INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(self::CREATE_MM_GAMES_SQL);
        $this->addSql(self::CREATE_MM_BRACKETS_SQL);
        //$this->addSql('CREATE INDEX IDX_7421286E8D78 ON mm_games (bracket_id)');
        //$this->addSql('CREATE UNIQUE INDEX UNIQ_742128727ACA70 ON mm_games (parent_id)');
        //$this->addSql('CREATE UNIQUE INDEX UNIQ_742128DD62C21B ON mm_games (child_id)');
        //$this->addSql('ALTER TABLE mm_games ADD CONSTRAINT FK_7421286E8D78 FOREIGN KEY (bracket_id) REFERENCES mm_brackets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        //$this->addSql('ALTER TABLE mm_games ADD CONSTRAINT FK_742128727ACA70 FOREIGN KEY (parent_id) REFERENCES mm_games (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        //$this->addSql('ALTER TABLE mm_games ADD CONSTRAINT FK_742128DD62C21B FOREIGN KEY (child_id) REFERENCES mm_games (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP SEQUENCE seq_mm_games');
        $this->addSql('DROP SEQUENCE seq_mm_brackets');
        $this->addSql('DROP TABLE mm_games');
        $this->addSql('DROP TABLE mm_brackets');
    }
}
