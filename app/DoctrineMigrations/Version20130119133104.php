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
  , nfcHalfScore INT NOT NULL
  , afcHalfScore INT NOT NULL
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
