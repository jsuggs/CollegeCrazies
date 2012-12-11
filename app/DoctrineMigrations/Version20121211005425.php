<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121211005425 extends AbstractMigration
{
    const SQL_INVITES_CREATE =<<<EOF
CREATE TABLE invites (
    id INT NOT NULL
  , user_id INT DEFAULT NULL
  , email VARCHAR(255) NOT NULL
  , createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
  , PRIMARY KEY(id)
);
EOF;

    public function up(Schema $schema)
    {
        $this->addSql('CREATE SEQUENCE seq_invites INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(self::SQL_INVITES_CREATE);
        $this->addSql('ALTER TABLE invites ADD CONSTRAINT FK_INVITES_REF_USERS_USER_ID FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_INVITES_USER_ID ON invites (user_id)');
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP SEQUENCE seq_invites');
        $this->addSql('DROP TABLE invites');
    }
}
