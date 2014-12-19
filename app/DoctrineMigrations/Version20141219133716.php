<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20141219133716 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE bp_seasons ADD champ_team_id VARCHAR(5) DEFAULT NULL");
        $this->addSql("ALTER TABLE bp_seasons ADD CONSTRAINT FK_63A571E6EFF509B3 FOREIGN KEY (champ_team_id) REFERENCES ncaa_teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("CREATE INDEX IDX_63A571E6EFF509B3 ON bp_seasons (champ_team_id)");
    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE bp_seasons DROP CONSTRAINT FK_63A571E6EFF509B3");
        $this->addSql("DROP INDEX IDX_63A571E6EFF509B3");
        $this->addSql("ALTER TABLE bp_seasons DROP champ_team_id");
    }
}
