<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20140107234702 extends AbstractMigration
{
    const SQUARES_GAME_PAYOUTS =<<<SQL
CREATE TABLE squares_game_payouts (
    id INT NOT NULL
  , game_id INT DEFAULT NULL
  , description VARCHAR(255) NOT NULL
  , percentage VARCHAR(255) NOT NULL
  , PRIMARY KEY(id)
)
SQL;

    const SQUARES_GAMES =<<<SQL
CREATE TABLE squares_games (
    id INT NOT NULL
  , user_id INT DEFAULT NULL
  , name VARCHAR(255) NOT NULL
  , homeTeam VARCHAR(255) NOT NULL
  , awayTeam VARCHAR(255) NOT NULL
  , costPerSquare INT NOT NULL
  , PRIMARY KEY(id)
)
SQL;

    const SQUARES_SQUARES =<<<SQL
CREATE TABLE squares_squares (
    game_id INT DEFAULT NULL
  , row INT NOT NULL
  , col INT NOT NULL
  , owner_id INT DEFAULT NULL
  , PRIMARY KEY(game_id, row, col)
)
SQL;

    const SQUARES_PLAYERS =<<<SQL
CREATE TABLE squares_players (
    id INT NOT NULL
  , user_id INT DEFAULT NULL
  , game_id INT DEFAULT NULL
  , name VARCHAR(10) NOT NULL
  , color VARCHAR(6) NOT NULL
  , PRIMARY KEY(id)
)
SQL;

    public function up(Schema $schema)
    {
        $this->addSql("CREATE SEQUENCE seq_squares_payout INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql("CREATE SEQUENCE seq_squares_game INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql("CREATE SEQUENCE seq_squares_players INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql(self::SQUARES_GAME_PAYOUTS);
        $this->addSql("CREATE INDEX IDX_SQUARES_GAME_PAYOUTS_GAME_ID ON squares_game_payouts (game_id)");
        $this->addSql(self::SQUARES_GAMES);
        $this->addSql("CREATE INDEX IDX_SQUARES_GAMES_USER_ID ON squares_games (user_id)");
        $this->addSql(self::SQUARES_SQUARES);
        $this->addSql("CREATE INDEX IDX_5EB3DCDCE48FD905 ON squares_squares (game_id)");
        $this->addSql("ALTER TABLE squares_game_payouts ADD CONSTRAINT FK_SQUARES_GAME_PAYOUTS_REF_SQUARES_GAMES_GAME_ID FOREIGN KEY (game_id) REFERENCES squares_games (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE squares_games ADD CONSTRAINT FK_SQUARES_GAMES_REF_USERS_USER_ID FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE squares_squares ADD CONSTRAINT FK_SQUARES_SQUARES_REF_SQUARES_GAMES_GAME_ID FOREIGN KEY (game_id) REFERENCES squares_games (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE squares_squares ADD CONSTRAINT FK_SQUARES_SQUARES_REF_USERS_OWNER_ID FOREIGN KEY (owner_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql(self::SQUARES_PLAYERS);
        $this->addSql("CREATE INDEX IDX_409126DDA76ED395 ON squares_players (user_id)");
        $this->addSql("CREATE INDEX IDX_409126DDE48FD905 ON squares_players (game_id)");
        $this->addSql("ALTER TABLE squares_players ADD CONSTRAINT FK_409126DDA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE squares_players ADD CONSTRAINT FK_409126DDE48FD905 FOREIGN KEY (game_id) REFERENCES squares_games (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
    }

    public function down(Schema $schema)
    {
        $this->addSql("DROP SEQUENCE seq_squares_payout CASCADE");
        $this->addSql("DROP SEQUENCE seq_squares_game CASCADE");
        $this->addSql("DROP SEQUENCE seq_squares_players CASCADE");
        $this->addSql("DROP TABLE squares_game_payouts CASCADE");
        $this->addSql("DROP TABLE squares_games CASCADE");
        $this->addSql("DROP TABLE squares_squares CASCADE");
        $this->addSql("DROP TABLE squares_players CASCADE");
    }
}