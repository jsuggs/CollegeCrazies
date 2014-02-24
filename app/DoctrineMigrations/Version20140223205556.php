<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20140223205556 extends AbstractMigration
{
    const CORE_PEOPLE =<<<SQL
CREATE TABLE core_people (
    id INT NOT NULL
  , firstName VARCHAR(32) NOT NULL
  , lastName VARCHAR(32) NOT NULL
  , birthDate DATE DEFAULT NULL
  , birthPlace VARCHAR(64) DEFAULT NULL
  , PRIMARY KEY(id)
)
SQL;

    public function up(Schema $schema)
    {
        $this->addSql("CREATE SEQUENCE seq_core_people INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql(self::CORE_PEOPLE);
    }

    public function down(Schema $schema)
    {
        $this->addSql("DROP SEQUENCE seq_core_people CASCADE");
        $this->addSql("DROP TABLE core_people");
    }
}
