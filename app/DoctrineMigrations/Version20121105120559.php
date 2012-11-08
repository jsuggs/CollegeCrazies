<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121105120559 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE games ADD spread DOUBLE PRECISION');
        $this->addSql('UPDATE games set spread = 0');
        $this->addSql('ALTER TABLE games ALTER spread SET NOT NULL');

        $this->addSql('ALTER TABLE games ADD overunder INT');
        $this->addSql('UPDATE games set overunder = 0');
        $this->addSql('ALTER TABLE games ALTER overunder SET NOT NULL');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE games DROP spread');
        $this->addSql('ALTER TABLE games DROP overunder');
    }
}
