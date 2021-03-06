<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20140120210651 extends AbstractMigration
{
    const SQUARES_LOG =<<<SQL
CREATE TABLE squares_log (
    id INT NOT NULL
  , game_id INT DEFAULT NULL
  , message VARCHAR(255) NOT NULL
  , createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
  , PRIMARY KEY(id)
)
SQL;

    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE squares_players ADD admin BOOLEAN DEFAULT 'false' NOT NULL");
        $this->addSql('CREATE SEQUENCE seq_squares_log INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(self::SQUARES_LOG);
        $this->addSql('ALTER TABLE squares_log ADD CONSTRAINT FK_SQUARES_LOG_REF_SQUARES_GAME_GAME_ID FOREIGN KEY (game_id) REFERENCES squares_games (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE squares_game_payouts ADD seq INT NOT NULL');
        $this->addSql('CREATE INDEX IDX_SQUARES_LOG_GAME_ID ON squares_log (game_id)');
        $this->addSql('ALTER TABLE squares_game_payouts ADD carryover BOOLEAN NOT NULL');
        $this->addSql("ALTER TABLE squares_games ADD forcewinner BOOLEAN DEFAULT 'false' NOT NULL");
        $this->addSql('ALTER TABLE invites ADD type VARCHAR(32)');
        $this->addSql("UPDATE invites set type='bp'");
        $this->addSql('ALTER TABLE invites ALTER COLUMN type SET NOT NULL');
        $this->addSql('ALTER TABLE invites ADD squares_game_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invites ADD CONSTRAINT FK_INVITES_REF_SQUARES_GAME_GAME_ID FOREIGN KEY (squares_game_id) REFERENCES squares_games (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_INVITES_SQUARES_GAME_ID ON invites (squares_game_id)');
        $this->addSql('ALTER TABLE squares_game_payouts ADD CONSTRAINT uniq_squares_payouts_game_id_seq UNIQUE (game_id, seq) DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE squares_players DROP admin');
        $this->addSql('DROP SEQUENCE seq_squares_log');
        $this->addSql('DROP TABLE squares_log');
        $this->addSql('ALTER TABLE squares_game_payouts DROP CONSTRAINT uniq_squares_payouts_game_id_seq');
        $this->addSql('ALTER TABLE squares_game_payouts DROP seq');
        $this->addSql('ALTER TABLE squares_game_payouts DROP carryover');
        $this->addSql('ALTER TABLE squares_games DROP forcewinner');
        $this->addSql('ALTER TABLE invites DROP type');
        $this->addSql('ALTER TABLE invites DROP squares_game_id');
    }
}
