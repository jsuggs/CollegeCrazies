<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121101214516 extends AbstractMigration
{
    const CREATE_LEAGUE_METADATA_SQL =<<<EOF
CREATE TABLE league_metadata (
    id INT NOT NULL
  , PRIMARY KEY(id)
);
EOF;

    const CREATE_LEAGUES_SQL =<<<EOF
CREATE TABLE leagues (
    id INT NOT NULL
  , metadata_id INT DEFAULT NULL
  , name VARCHAR(255) NOT NULL
  , password VARCHAR(255) NOT NULL
  , lockTime TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
  , public BOOLEAN NOT NULL
  , PRIMARY KEY(id)
);
EOF;

    public function up(Schema $schema)
    {
        $this->addSql('CREATE SEQUENCE seq_league_metadata INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(self::CREATE_LEAGUE_METADATA_SQL);

        $this->addSql('CREATE SEQUENCE seq_league INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(self::CREATE_LEAGUES_SQL);
        $this->addSql('ALTER TABLE leagues ADD CONSTRAINT FK_LEAGUES_REF_LEAGE_METADATA FOREIGN KEY (metadata_id) REFERENCES league_metadata (id) NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_LEAGUES_METADATA_ID ON leagues (metadata_id)');
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP SEQUENCE seq_league');
        $this->addSql('DROP TABLE leagues');

        $this->addSql('DROP SEQUENCE seq_league_metadata');
        $this->addSql('DROP TABLE league_metadata');
    }
}
