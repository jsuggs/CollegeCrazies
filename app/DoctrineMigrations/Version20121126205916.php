<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121126205916 extends AbstractMigration
{
    const CREATE_PICKSET_LEAGUES_SQL =<<<EOF
CREATE TABLE pickset_leagues (
    league_id INT NOT NULL
  , pickset_id INT NOT NULL
  , PRIMARY KEY(league_id, pickset_id)
);
EOF;

    public function up(Schema $schema)
    {
        $this->addSql(self::CREATE_PICKSET_LEAGUES_SQL);
        $this->addSql('CREATE INDEX IDX_FD8A632158AFC4DE ON pickset_leagues (league_id);');
        $this->addSql('CREATE INDEX IDX_FD8A632193EFC9ED ON pickset_leagues (pickset_id);');
        $this->addSql('ALTER TABLE pickset_leagues ADD CONSTRAINT FK_FD8A632158AFC4DE FOREIGN KEY (league_id) REFERENCES leagues (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->addSql('ALTER TABLE pickset_leagues ADD CONSTRAINT FK_FD8A632193EFC9ED FOREIGN KEY (pickset_id) REFERENCES picksets (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->addSql('ALTER TABLE picksets DROP league_id;');
        //$this->addSql('ALTER TABLE picksets DROP CONSTRAINT fk_picksets_ref_leagues_league_id;');
        //$this->addSql('DROP INDEX idx_picksets_league_id;');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE picksets ADD league_id INT DEFAULT NULL;');
        $this->addSql('ALTER TABLE picksets ADD CONSTRAINT FK_217E6AA58AFC4DE FOREIGN KEY (league_id) REFERENCES leagues (id) NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->addSql('CREATE INDEX IDX_217E6AA58AFC4DE ON picksets (league_id);');
    }
}
