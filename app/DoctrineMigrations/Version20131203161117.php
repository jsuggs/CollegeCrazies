<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20131203161117 extends AbstractMigration
{
    const CREATE_BP_EXPERT_PICKS =<<<SQL
CREATE TABLE bp_expert_picks (
    id INT NOT NULL
  , game_id INT DEFAULT NULL
  , team_id VARCHAR(5) DEFAULT NULL
  , expert_id INT DEFAULT NULL
  , description TEXT NOT NULL
  , createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
  , PRIMARY KEY(id)
)
SQL;

    const CREATE_BP_EXPERTS =<<<SQL
CREATE TABLE bp_experts (
    id INT NOT NULL
  , name VARCHAR(255) NOT NULL
  , PRIMARY KEY(id)
)
SQL;

    public function up(Schema $schema)
    {
        $this->addSql("CREATE SEQUENCE seq_expert_picks INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql("CREATE SEQUENCE seq_experts INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql(self::CREATE_BP_EXPERT_PICKS);
        $this->addSql("CREATE INDEX IDX_5CB1286BE48FD905 ON bp_expert_picks (game_id)");
        $this->addSql("CREATE INDEX IDX_5CB1286B296CD8AE ON bp_expert_picks (team_id)");
        $this->addSql("CREATE INDEX IDX_5CB1286BC5568CE4 ON bp_expert_picks (expert_id)");
        $this->addSql(self::CREATE_BP_EXPERTS);
        $this->addSql("ALTER TABLE bp_expert_picks ADD CONSTRAINT FK_5CB1286BE48FD905 FOREIGN KEY (game_id) REFERENCES games (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE bp_expert_picks ADD CONSTRAINT FK_5CB1286B296CD8AE FOREIGN KEY (team_id) REFERENCES teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE bp_expert_picks ADD CONSTRAINT FK_5CB1286BC5568CE4 FOREIGN KEY (expert_id) REFERENCES bp_experts (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE bp_expert_picks DROP CONSTRAINT FK_5CB1286BC5568CE4");
        $this->addSql("DROP SEQUENCE seq_expert_picks CASCADE");
        $this->addSql("DROP SEQUENCE seq_experts CASCADE");
        $this->addSql("DROP TABLE bp_expert_picks");
        $this->addSql("DROP TABLE bp_experts");
    }
}
