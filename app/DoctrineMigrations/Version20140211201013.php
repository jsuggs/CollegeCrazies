<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20140211201013 extends AbstractMigration
{
    const PIRC_PORTFOLIOS =<<<SQL
CREATE TABLE pirc_portfolios (
    id INT NOT NULL
  , name VARCHAR(255) NOT NULL
  , user_id INT NOT NULL
  , game_id INT NOT NULL
  , score SMALLINT DEFAULT NULL
  , PRIMARY KEY(id)
)
SQL;

    const PIRC_CONFIG =<<<SQL
CREATE TABLE pirc_config (
    id INT NOT NULL
  , game_id INT DEFAULT NULL
  , seed1Cost SMALLINT NOT NULL
  , seed2Cost SMALLINT NOT NULL
  , seed3Cost SMALLINT NOT NULL
  , seed4Cost SMALLINT NOT NULL
  , seed5Cost SMALLINT NOT NULL
  , seed6Cost SMALLINT NOT NULL
  , seed7Cost SMALLINT NOT NULL
  , seed8Cost SMALLINT NOT NULL
  , seed9Cost SMALLINT NOT NULL
  , seed10Cost SMALLINT NOT NULL
  , seed11Cost SMALLINT NOT NULL
  , seed12Cost SMALLINT NOT NULL
  , seed13Cost SMALLINT NOT NULL
  , seed14Cost SMALLINT NOT NULL
  , seed15Cost SMALLINT NOT NULL
  , seed16Cost SMALLINT NOT NULL
  , round1Win SMALLINT NOT NULL
  , round2Win SMALLINT NOT NULL
  , round3Win SMALLINT NOT NULL
  , round4Win SMALLINT NOT NULL
  , round5Win SMALLINT NOT NULL
  , round6Win SMALLINT NOT NULL
  , PRIMARY KEY(id)
)
SQL;

    const PIRC_GAMES =<<<SQL
CREATE TABLE pirc_games (
    id INT NOT NULL
  , name VARCHAR(255) NOT NULL
  , season SMALLINT NOT NULL
  , password VARCHAR(255) DEFAULT NULL
  , PRIMARY KEY(id)
)
SQL;

    const PIRC_GAME_MANAGERS =<<<SQL
CREATE TABLE pirc_game_managers (
    user_id INT NOT NULL
  , game_id INT NOT NULL
  , PRIMARY KEY(user_id, game_id)
)
SQL;

    const PIRC_PORTFOLIO_TEAMS =<<<SQL
CREATE TABLE pirc_portfolio_teams (
    portfolio_id INT NOT NULL
  , team_id VARCHAR(5) NOT NULL
  , round1Score SMALLINT DEFAULT NULL
  , round2Score SMALLINT DEFAULT NULL
  , round3Score SMALLINT DEFAULT NULL
  , round4Score SMALLINT DEFAULT NULL
  , round5Score SMALLINT DEFAULT NULL
  , round6Score SMALLINT DEFAULT NULL
  , PRIMARY KEY(portfolio_id, team_id)
)
SQL;

    public function up(Schema $schema)
    {
        $this->addSql("CREATE SEQUENCE seq_pirc_games INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql("CREATE SEQUENCE seq_pirc_config INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql("CREATE SEQUENCE seq_pirc_portfolios INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql(self::PIRC_PORTFOLIOS);
        $this->addSql("CREATE INDEX IDX_C6B7BDEEA76ED395 ON pirc_portfolios (user_id)");
        $this->addSql("CREATE INDEX IDX_C6B7BDEEE48FD905 ON pirc_portfolios (game_id)");
        $this->addSql(self::PIRC_CONFIG);
        $this->addSql("CREATE UNIQUE INDEX UNIQ_88B3972FE48FD905 ON pirc_config (game_id)");
        $this->addSql(self::PIRC_GAMES);
        $this->addSql("CREATE INDEX IDX_75F17C8AF0E45BA9 ON pirc_games (season)");
        $this->addSql(self::PIRC_GAME_MANAGERS);
        $this->addSql("CREATE INDEX IDX_CB4317FFA76ED395 ON pirc_game_managers (user_id)");
        $this->addSql("CREATE INDEX IDX_CB4317FFE48FD905 ON pirc_game_managers (game_id)");
        $this->addSql(self::PIRC_PORTFOLIO_TEAMS);
        $this->addSql("CREATE INDEX IDX_C3E190B1B96B5643 ON pirc_portfolio_teams (portfolio_id)");
        $this->addSql("CREATE INDEX IDX_C3E190B1296CD8AE ON pirc_portfolio_teams (team_id)");
        $this->addSql("ALTER TABLE pirc_games ADD CONSTRAINT FK_75F17C8AF0E45BA9 FOREIGN KEY (season) REFERENCES mm_brackets (season) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE pirc_portfolios ADD CONSTRAINT FK_C6B7BDEEA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE pirc_portfolios ADD CONSTRAINT FK_C6B7BDEEE48FD905 FOREIGN KEY (game_id) REFERENCES pirc_games (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE pirc_config ADD CONSTRAINT FK_88B3972FE48FD905 FOREIGN KEY (game_id) REFERENCES pirc_games (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE pirc_game_managers ADD CONSTRAINT FK_CB4317FFA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE pirc_game_managers ADD CONSTRAINT FK_CB4317FFE48FD905 FOREIGN KEY (game_id) REFERENCES pirc_games (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE pirc_portfolio_teams ADD CONSTRAINT FK_C3E190B1B96B5643 FOREIGN KEY (portfolio_id) REFERENCES pirc_portfolios (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE pirc_portfolio_teams ADD CONSTRAINT FK_C3E190B1296CD8AE FOREIGN KEY (team_id) REFERENCES ncaa_teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE invites ADD pirc_game_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE invites ADD CONSTRAINT FK_INVITES_REF_PIRC_GAMES_PIRC_GAME_ID FOREIGN KEY (pirc_game_id) REFERENCES pirc_games (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("CREATE INDEX IDX_INVITES_PIRC_GAME_ID ON invites (pirc_game_id)");
    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE pirc_config DROP CONSTRAINT FK_88B3972FE48FD905");
        $this->addSql("ALTER TABLE pirc_game_managers DROP CONSTRAINT FK_CB4317FFE48FD905");
        $this->addSql("ALTER TABLE pirc_portfolio_teams DROP CONSTRAINT FK_C3E190B1B96B5643");
        $this->addSql("ALTER TABLE pirc_portfolio_teams DROP CONSTRAINT FK_C3E190B1296CD8AE");
        $this->addSql("DROP SEQUENCE seq_pirc_games CASCADE");
        $this->addSql("DROP SEQUENCE seq_pirc_config CASCADE");
        $this->addSql("DROP SEQUENCE seq_pirc_portfolios CASCADE");
        $this->addSql("ALTER TABLE invites DROP pirc_game_id");
        $this->addSql("DROP TABLE pirc_portfolios");
        $this->addSql("DROP TABLE pirc_config");
        $this->addSql("DROP TABLE pirc_games");
        $this->addSql("DROP TABLE pirc_game_managers");
        $this->addSql("DROP TABLE pirc_portfolio_teams");
    }
}
