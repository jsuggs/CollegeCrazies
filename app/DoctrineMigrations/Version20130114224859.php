<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20130114224859 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE games ALTER COLUMN shortname DROP DEFAULT');
        $this->addSql('ALTER TABLE teams ALTER thumbnail DROP DEFAULT');
        $this->addSql('ALTER TABLE teams ALTER thumbnail DROP DEFAULT');
        $this->addSql('ALTER TABLE users ALTER emailvisible DROP DEFAULT');
        $this->addSql('ALTER TABLE users ALTER firstname DROP DEFAULT');
        $this->addSql('ALTER TABLE users ALTER lastname DROP DEFAULT');
        $this->addSql('ALTER TABLE users ALTER emailfromcommish DROP DEFAULT');
        $this->addSql('ALTER TABLE leagues ALTER locked DROP DEFAULT');
    }

    public function down(Schema $schema)
    {
    }
}
