<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20130211203847 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('CREATE SEQUENCE seq_mm_user_brackets INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE mm_user_brackets (id INT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4DA3E3FFA76ED395 ON mm_user_brackets (user_id)');
        $this->addSql('CREATE TABLE mm_picks (id INT NOT NULL, bracket_id INT DEFAULT NULL, game_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, homeTeamScore INT DEFAULT NULL, awayTeamScore INT DEFAULT NULL, gameDate TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, location VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_831D42D16E8D78 ON mm_picks (bracket_id)');
        $this->addSql('CREATE INDEX IDX_831D42D1E48FD905 ON mm_picks (game_id)');
        $this->addSql('ALTER TABLE mm_user_brackets ADD CONSTRAINT FK_4DA3E3FFA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mm_picks ADD CONSTRAINT FK_831D42D16E8D78 FOREIGN KEY (bracket_id) REFERENCES mm_user_brackets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        //$this->addSql('ALTER TABLE mm_picks ADD CONSTRAINT FK_831D42D1E48FD905 FOREIGN KEY (game_id) REFERENCES mm_games (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP SEQUENCE seq_mm_user_brackets');
        $this->addSql('DROP SEQUENCE seq_mm_games');
        $this->addSql('DROP TABLE mm_user_brackets');
    }
}
