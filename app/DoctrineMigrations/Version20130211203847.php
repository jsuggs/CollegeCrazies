<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20130211203847 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('CREATE SEQUENCE seq_mm_user_brackets INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE seq_mm_games INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE mm_user_brackets (id INT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4DA3E3FFA76ED395 ON mm_user_brackets (user_id)');
        $this->addSql('CREATE TABLE mm_games (id INT NOT NULL, year INT DEFAULT NULL, parent_id INT DEFAULT NULL, child_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, homeTeamScore INT DEFAULT NULL, awayTeamScore INT DEFAULT NULL, gameDate TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, location VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_742128BB827337 ON mm_games (year)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_742128727ACA70 ON mm_games (parent_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_742128DD62C21B ON mm_games (child_id)');
        $this->addSql('CREATE TABLE mm_brackets (year INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(year))');
        $this->addSql('CREATE TABLE mm_picks (id INT NOT NULL, bracket_id INT DEFAULT NULL, game_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, homeTeamScore INT DEFAULT NULL, awayTeamScore INT DEFAULT NULL, gameDate TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, location VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_831D42D16E8D78 ON mm_picks (bracket_id)');
        $this->addSql('CREATE INDEX IDX_831D42D1E48FD905 ON mm_picks (game_id)');
        $this->addSql('ALTER TABLE mm_user_brackets ADD CONSTRAINT FK_4DA3E3FFA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mm_games ADD CONSTRAINT FK_742128BB827337 FOREIGN KEY (year) REFERENCES mm_brackets (year) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mm_games ADD CONSTRAINT FK_742128727ACA70 FOREIGN KEY (parent_id) REFERENCES mm_games (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mm_games ADD CONSTRAINT FK_742128DD62C21B FOREIGN KEY (child_id) REFERENCES mm_games (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mm_picks ADD CONSTRAINT FK_831D42D16E8D78 FOREIGN KEY (bracket_id) REFERENCES mm_user_brackets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mm_picks ADD CONSTRAINT FK_831D42D1E48FD905 FOREIGN KEY (game_id) REFERENCES mm_games (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP SEQUENCE seq_mm_user_brackets');
        $this->addSql('DROP SEQUENCE seq_mm_games');
        $this->addSql('DROP TABLE mm_picks');
        $this->addSql('DROP TABLE mm_games');
        $this->addSql('DROP TABLE mm_user_brackets');
        $this->addSql('DROP TABLE mm_brackets');
    }
}
