<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20140211201013 extends AbstractMigration
{
    const PIRC_PORTFOLIOS =<<<SQL
CREATE TABLE pirc_portfolios (
    id INT NOT NULL
  , user_id INT NOT NULL
  , season SMALLINT NOT NULL
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
  , password VARCHAR(255) DEFAULT NULL
  , PRIMARY KEY(id)
)
SQL;

    const PIRC_GAME_PORTFOLIOS =<<<SQL
CREATE TABLE pirc_game_portfolios (
    game_id INT NOT NULL
  , portfolio_id INT NOT NULL
  , PRIMARY KEY(game_id, portfolio_id)
)
SQL;

    const PIRC_GAME_MANAGERS =<<<SQL
CREATE TABLE pirc_game_managers (
    user_id INT NOT NULL
  , game_id INT NOT NULL
  , PRIMARY KEY(user_id, game_id)
)
SQL;

    public function up(Schema $schema)
    {
        $this->addSql("CREATE SEQUENCE seq_pirc_games INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql("CREATE SEQUENCE seq_pirc_config INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql(self::PIRC_PORTFOLIOS);
        $this->addSql("CREATE INDEX IDX_C6B7BDEEA76ED395 ON pirc_portfolios (user_id)");
        $this->addSql("CREATE INDEX IDX_C6B7BDEEF0E45BA9 ON pirc_portfolios (season)");
        $this->addSql(self::PIRC_CONFIG);
        $this->addSql("CREATE UNIQUE INDEX UNIQ_88B3972FE48FD905 ON pirc_config (game_id)");
        $this->addSql(self::PIRC_GAMES);
        $this->addSql(self::PIRC_GAME_PORTFOLIOS);
        $this->addSql("CREATE INDEX IDX_5EBC7D2EE48FD905 ON pirc_game_portfolios (game_id)");
        $this->addSql("CREATE INDEX IDX_5EBC7D2EB96B5643 ON pirc_game_portfolios (portfolio_id)");
        $this->addSql(self::PIRC_GAME_MANAGERS);
        $this->addSql("CREATE INDEX IDX_CB4317FFA76ED395 ON pirc_game_managers (user_id)");
        $this->addSql("CREATE INDEX IDX_CB4317FFE48FD905 ON pirc_game_managers (game_id)");
        $this->addSql("ALTER TABLE pirc_portfolios ADD CONSTRAINT FK_C6B7BDEEA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE pirc_portfolios ADD CONSTRAINT FK_C6B7BDEEF0E45BA9 FOREIGN KEY (season) REFERENCES mm_brackets (season) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE pirc_config ADD CONSTRAINT FK_88B3972FE48FD905 FOREIGN KEY (game_id) REFERENCES pirc_games (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE pirc_game_portfolios ADD CONSTRAINT FK_5EBC7D2EE48FD905 FOREIGN KEY (game_id) REFERENCES pirc_games (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE pirc_game_portfolios ADD CONSTRAINT FK_5EBC7D2EB96B5643 FOREIGN KEY (portfolio_id) REFERENCES pirc_portfolios (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE pirc_game_managers ADD CONSTRAINT FK_CB4317FFA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE pirc_game_managers ADD CONSTRAINT FK_CB4317FFE48FD905 FOREIGN KEY (game_id) REFERENCES pirc_games (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE pirc_game_portfolios DROP CONSTRAINT FK_5EBC7D2EB96B5643");
        $this->addSql("ALTER TABLE pirc_config DROP CONSTRAINT FK_88B3972FE48FD905");
        $this->addSql("ALTER TABLE pirc_game_portfolios DROP CONSTRAINT FK_5EBC7D2EE48FD905");
        $this->addSql("ALTER TABLE pirc_game_managers DROP CONSTRAINT FK_CB4317FFE48FD905");
        $this->addSql("DROP SEQUENCE seq_pirc_games CASCADE");
        $this->addSql("DROP SEQUENCE seq_pirc_config CASCADE");
        $this->addSql("DROP TABLE pirc_portfolios");
        $this->addSql("DROP TABLE pirc_config");
        $this->addSql("DROP TABLE pirc_games");
        $this->addSql("DROP TABLE pirc_game_portfolios");
        $this->addSql("DROP TABLE pirc_game_managers");
    }
}
