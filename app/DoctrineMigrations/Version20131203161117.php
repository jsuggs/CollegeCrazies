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
        $this->addSql("CREATE INDEX IDX_BP_EXPERT_PICKS_GAME_ID ON bp_expert_picks (game_id)");
        $this->addSql("CREATE INDEX IDX_BP_EXPERT_PICKS_TEAM_ID ON bp_expert_picks (team_id)");
        $this->addSql("CREATE INDEX IDX_BP_EXPERT_PICKS_EXPERT_ID ON bp_expert_picks (expert_id)");
        $this->addSql(self::CREATE_BP_EXPERTS);
        $this->addSql("ALTER TABLE bp_expert_picks ADD CONSTRAINT FK_BP_EXPERT_PICKS_REFS_GAMES_GAME_ID FOREIGN KEY (game_id) REFERENCES games (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE bp_expert_picks ADD CONSTRAINT FK_BP_EXPERT_PICKS_REFS_TEAMS_TEAM_ID FOREIGN KEY (team_id) REFERENCES teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE bp_expert_picks ADD CONSTRAINT FK_BP_EXPERT_PICKS_REFS_BP_EXPERTS_EXPERT_ID FOREIGN KEY (expert_id) REFERENCES bp_experts (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE bp_expert_picks DROP CONSTRAINT FK_BP_EXPERT_PICKS_REFS_BP_EXPERTS_EXPERT_ID");
        $this->addSql("DROP TABLE bp_expert_picks");
        $this->addSql("DROP TABLE bp_experts");
        $this->addSql("DROP SEQUENCE seq_expert_picks CASCADE");
        $this->addSql("DROP SEQUENCE seq_experts CASCADE");
    }
}
