<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121101220750 extends AbstractMigration
{
    const SQL_PICKS_CREATE =<<<EOF
CREATE TABLE picks (
    id INT NOT NULL
  , game_id INT DEFAULT NULL
  , team_id VARCHAR(5) DEFAULT NULL
  , confidence INT NOT NULL
  , pickSet_id INT DEFAULT NULL
  , PRIMARY KEY(id)
);
EOF;

    public function up(Schema $schema)
    {
        $this->addSql('CREATE SEQUENCE seq_pick INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(self::SQL_PICKS_CREATE);
        $this->addSql('ALTER TABLE picks ADD CONSTRAINT FK_PICKS_REF_PICKSETS_PICKSET_ID FOREIGN KEY (pickSet_id) REFERENCES picksets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE picks ADD CONSTRAINT FK_PICKS_REF_GAMES_GAME_ID FOREIGN KEY (game_id) REFERENCES games (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE picks ADD CONSTRAINT FK_PICKS_REF_TEAMS_TEAM_ID FOREIGN KEY (team_id) REFERENCES teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_PICKS_PICKSET_ID ON picks (pickSet_id)');
        $this->addSql('CREATE INDEX IDX_PICKS_GAME_ID ON picks (game_id)');
        $this->addSql('CREATE INDEX IDX_PICKS_TEAM_ID ON picks (team_id)');
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP SEQUENCE seq_pick');
        $this->addSql('DROP TABLE picks');
    }
}
