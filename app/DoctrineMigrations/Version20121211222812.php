<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121211222812 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE picksets ALTER name TYPE VARCHAR(40)');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE picksets ALTER name TYPE VARCHAR(50)');
    }
}
