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
  , percentage SMALLINT NOT NULL
  , homeTeamResult SMALLINT DEFAULT NULL
  , awayTeamResult SMALLINT DEFAULT NULL
  , winner_id INT DEFAULT NULL
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
  , locked BOOLEAN NOT NULL
  , row0 SMALLINT NOT NULL
  , row1 SMALLINT NOT NULL
  , row2 SMALLINT NOT NULL
  , row3 SMALLINT NOT NULL
  , row4 SMALLINT NOT NULL
  , row5 SMALLINT NOT NULL
  , row6 SMALLINT NOT NULL
  , row7 SMALLINT NOT NULL
  , row8 SMALLINT NOT NULL
  , row9 SMALLINT NOT NULL
  , col0 SMALLINT NOT NULL
  , col1 SMALLINT NOT NULL
  , col2 SMALLINT NOT NULL
  , col3 SMALLINT NOT NULL
  , col4 SMALLINT NOT NULL
  , col5 SMALLINT NOT NULL
  , col6 SMALLINT NOT NULL
  , col7 SMALLINT NOT NULL
  , col8 SMALLINT NOT NULL
  , col9 SMALLINT NOT NULL
  , PRIMARY KEY(id)
)
SQL;

    const SQUARES_SQUARES =<<<SQL
CREATE TABLE squares_squares (
    game_id INT DEFAULT NULL
  , row INT NOT NULL
  , col INT NOT NULL
  , player_id INT DEFAULT NULL
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
        $this->addSql("CREATE INDEX IDX_SQUARES_SQUARES_GAME_ID ON squares_squares (game_id)");
        $this->addSql("ALTER TABLE squares_game_payouts ADD CONSTRAINT FK_SQUARES_GAME_PAYOUTS_REF_SQUARES_GAMES_GAME_ID FOREIGN KEY (game_id) REFERENCES squares_games (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE squares_games ADD CONSTRAINT FK_SQUARES_GAMES_REF_USERS_USER_ID FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE squares_squares ADD CONSTRAINT FK_SQUARES_SQUARES_REF_SQUARES_GAMES_GAME_ID FOREIGN KEY (game_id) REFERENCES squares_games (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql(self::SQUARES_PLAYERS);
        $this->addSql("CREATE INDEX IDX_SQUARES_PLAYERS_USER_ID ON squares_players (user_id)");
        $this->addSql("CREATE INDEX IDX_SQUARES_PLAYERS_GAME_ID ON squares_players (game_id)");
        $this->addSql("ALTER TABLE squares_squares ADD CONSTRAINT FK_SQUARES_SQUARES_REF_PLAYERS_PLAYER_ID FOREIGN KEY (player_id) REFERENCES squares_players (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE squares_players ADD CONSTRAINT FK_SQUARES_PLAYERS_REF_USERS_USER_ID FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE squares_players ADD CONSTRAINT FK_SQUARES_PLAYERS_REF_SQUARES_GAMES_GAME_ID FOREIGN KEY (game_id) REFERENCES squares_games (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
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
