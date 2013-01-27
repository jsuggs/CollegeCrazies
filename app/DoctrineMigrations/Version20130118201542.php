<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20130118201542 extends AbstractMigration
{
    const SQL_SBC_CONFIG_CREATE =<<<EOF
CREATE TABLE sbc_config (
    year INT NOT NULL
  , startTime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
  , closeTime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
  , scoresCalculated BOOLEAN NOT NULL
  , nfcTeam_id VARCHAR(3) DEFAULT NULL
  , afcTeam_id VARCHAR(3) DEFAULT NULL
  , finalScorePoints INT NOT NULL
  , halftimeScorePoints INT NOT NULL
  , firstTeamToScoreInAQuarterPoints INT NOT NULL
  , neitherTeamToScoreInAQuarterPoints INT NOT NULL
  , bonusQuestionPoints INT NOT NULL
  , PRIMARY KEY(year)
);
EOF;

    public function up(Schema $schema)
    {
        $this->addSql(self::SQL_SBC_CONFIG_CREATE);
        $this->addSql('ALTER TABLE sbc_config ADD CONSTRAINT FK_SBC_CONFIG_REF_NFL_TEAM_NFC_TEAM FOREIGN KEY (nfcTeam_id) REFERENCES nfl_team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sbc_config ADD CONSTRAINT FK_SBC_CONFIG_REF_NFL_TEAM_ACF_TEAM FOREIGN KEY (afcTeam_id) REFERENCES nfl_team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_SBC_CONFIG_NFC_TEAM_ID ON sbc_config (nfcTeam_id)');
        $this->addSql('CREATE INDEX IDX_SBC_CONFIG_AFC_TEAM_ID ON sbc_config (afcTeam_id)');

        $this->addSql("INSERT INTO sbc_config (year, startTime, closeTime, scoresCalculated, nfcTeam_id, afcTeam_id, finalScorePoints, halftimeScorePoints, firstTeamToScoreInAQuarterPoints , neitherTeamToScoreInAQuarterPoints , bonusQuestionPoints) VALUES (2013, '2013-01-13', '2013-02-03', false, 'SF', 'BAL', 150, 100, 25, 40, 25)");
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE sbc_config');
    }
}
