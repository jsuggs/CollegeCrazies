<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20141208211508 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("CREATE TABLE bp_seasons (season INT NOT NULL, hasChampionship BOOLEAN NOT NULL, PRIMARY KEY(season))");
        for ($year = 2012; $year < 2015; $year++) {
            $this->addSql(sprintf('INSERT INTO bp_seasons (season, hasChampionship) VALUES (%d, %s)', $year, $year >= 2014 ? 'true' : 'false'));
        }
        $this->addSql("ALTER TABLE picksets ADD champ_team_id VARCHAR(5) DEFAULT NULL");
        $this->addSql("ALTER TABLE picksets ALTER season DROP NOT NULL");
        $this->addSql("ALTER TABLE picksets ADD CONSTRAINT FK_217E6AAF0E45BA9 FOREIGN KEY (season) REFERENCES bp_seasons (season) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE picksets ADD CONSTRAINT FK_217E6AAEFF509B3 FOREIGN KEY (champ_team_id) REFERENCES ncaa_teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("CREATE INDEX IDX_217E6AAF0E45BA9 ON picksets (season)");
        $this->addSql("CREATE INDEX IDX_217E6AAEFF509B3 ON picksets (champ_team_id)");
    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE picksets DROP CONSTRAINT FK_217E6AAF0E45BA9");
        $this->addSql("DROP TABLE bp_seasons");
        $this->addSql("DROP INDEX IDX_217E6AAF0E45BA9");
        $this->addSql("DROP INDEX IDX_217E6AAEFF509B3");
        $this->addSql("ALTER TABLE picksets DROP champ_team_id");
        $this->addSql("ALTER TABLE picksets ALTER season SET NOT NULL");
    }
}
