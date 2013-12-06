<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20131205170907 extends AbstractMigration
{
    const CORE_IMAGE =<<<SQL
CREATE TABLE core_images (
    id SERIAL NOT NULL
  , name VARCHAR(255) NOT NULL
  , size INT DEFAULT NULL
  , created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
  , content_type VARCHAR(50) DEFAULT NULL
  , path VARCHAR(255) NOT NULL
, PRIMARY KEY(id)
)
SQL;

    public function up(Schema $schema)
    {
        $this->addSql(self::CORE_IMAGE);
        $this->addSql('ALTER TABLE leagues ADD logo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE leagues ADD CONSTRAINT FK_LEAGUES_REF_CORE_IMAGES_LOGO_ID FOREIGN KEY (logo_id) REFERENCES core_images (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE leagues DROP logo_id");
        $this->addSql("DROP TABLE core_images");
    }
}
