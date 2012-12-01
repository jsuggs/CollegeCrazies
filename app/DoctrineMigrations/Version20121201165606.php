<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121201165606 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE users ADD emailVisible BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE users ADD firstName VARCHAR(255) DEFAULT \'\'');
        $this->addSql('ALTER TABLE users ADD lastName VARCHAR(255) DEFAULT \'\'');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE users DROP emailVisible');
        $this->addSql('ALTER TABLE users DROP firstName');
        $this->addSql('ALTER TABLE users DROP lastName');
    }
}
