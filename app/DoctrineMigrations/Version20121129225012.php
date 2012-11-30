<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121129225012 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE games ALTER overunder TYPE DOUBLE PRECISION');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE games ALTER overunder TYPE INT');
    }
}
