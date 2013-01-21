<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20130103221623 extends AbstractMigration
{
    const SQL_SBC_PICKS_CREATE =<<<EOF
CREATE TABLE sbc_picks (
    id INT NOT NULL
  , user_id INT DEFAULT NULL
  , year INT NOT NULL
  , nfcFinalScore INT NOT NULL
  , afcFinalScore INT NOT NULL
  , nfcHalftimeScore INT NOT NULL
  , afcHalftimeScore INT NOT NULL
  , firstTeamToScoreFirstQuarter VARCHAR(4) NOT NULL
  , firstTeamToScoreSecondQuarter VARCHAR(4) NOT NULL
  , firstTeamToScoreThirdQuarter VARCHAR(4) NOT NULL
  , firstTeamToScoreFourthQuarter VARCHAR(4) NOT NULL
  , totalPoints INT DEFAULT NULL
  , finalScorePoints INT DEFAULT NULL
  , firstTeamToScorePoints INT DEFAULT NULL
  , halftimeScorePoints INT DEFAULT NULL
  , PRIMARY KEY(id)
);
EOF;

    public function up(Schema $schema)
    {
        $this->addSql('CREATE SEQUENCE seq_sbc_pick INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(self::SQL_SBC_PICKS_CREATE);
        $this->addSql('CREATE INDEX IDX_SBC_PICKS_USER_ID ON sbc_picks (user_id)');
        $this->addSql('CREATE UNIQUE INDEX user_unique ON sbc_picks (user_id, year)');
        $this->addSql('ALTER TABLE sbc_picks ADD CONSTRAINT FK_SBC_PICKS_REF_USERS_USER_ID FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP SEQUENCE seq_sbc_pick');
        $this->addSql('DROP TABLE sbc_picks');
    }
}
