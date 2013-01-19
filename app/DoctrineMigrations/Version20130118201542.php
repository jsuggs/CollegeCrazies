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
  , finalScorePoints INT NOT NULL
  , PRIMARY KEY(year)
);
EOF;

    public function up(Schema $schema)
    {
        $this->addSql(self::SQL_SBC_CONFIG_CREATE);
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE sbc_config');
    }
}
