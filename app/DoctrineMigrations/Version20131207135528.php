<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20131207135528 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE users ADD referrer_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE users ADD CONSTRAINT FK_USERS_REF_USERS_REFERRER_ID FOREIGN KEY (referrer_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("CREATE INDEX IDX_USERS_REFERRER_ID ON users (referrer_id)");
    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE users DROP referrer_id");
    }
}
