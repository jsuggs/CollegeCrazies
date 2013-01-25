<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20130119133104 extends AbstractMigration
{
    const CREATE_SBC_RESULT_SQL =<<<EOF
CREATE TABLE sbc_results (
    year INT NOT NULL
  , nfcFinalScore INT NOT NULL
  , afcFinalScore INT NOT NULL
  , nfcHalftimeScore INT NOT NULL
  , afcHalftimeScore INT NOT NULL
  , firstTeamToScoreFirstQuarter VARCHAR(4) NOT NULL
  , firstTeamToScoreSecondQuarter VARCHAR(4) NOT NULL
  , firstTeamToScoreThirdQuarter VARCHAR(4) NOT NULL
  , firstTeamToScoreFourthQuarter VARCHAR(4) NOT NULL
  , bonusQuestion1 INT NOT NULL
  , bonusQuestion2 INT NOT NULL
  , bonusQuestion3 INT NOT NULL
  , bonusQuestion4 INT NOT NULL
  , PRIMARY KEY(year)
);
EOF;

    public function up(Schema $schema)
    {
        $this->addSql(self::CREATE_SBC_RESULT_SQL);
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE sbc_results');
    }
}
