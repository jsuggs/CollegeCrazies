<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20130123011617 extends AbstractMigration
{
    const CREATE_CORE_MODULE_CONFIG_SQL =<<<EOF
CREATE TABLE core_module_config (
    year INT NOT NULL
  , module_id VARCHAR(5) NOT NULL
  , startTime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
  , endTime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
  , PRIMARY KEY(year, module_id)
);
EOF;

    const CREATE_CORE_MODULE_SQL =<<<EOF
CREATE TABLE core_module (
    id VARCHAR(5) NOT NULL
  , name VARCHAR(255) NOT NULL
  , PRIMARY KEY(id)
);
EOF;

    public function up(Schema $schema)
    {
        $this->addSql(self::CREATE_CORE_MODULE_CONFIG_SQL);
        $this->addSql('CREATE INDEX IDX_F62C850CAFC2B591 ON core_module_config (module_id)');
        $this->addSql(self::CREATE_CORE_MODULE_SQL);
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE core_module');
        $this->addSql('DROP TABLE core_module_config');
    }
}
