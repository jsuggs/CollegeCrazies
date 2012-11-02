<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121101213317 extends AbstractMigration
{
    const CREATE_USERS_SQL = <<<EOF
CREATE TABLE users (
    id INT NOT NULL
  , username VARCHAR(255) NOT NULL
  , username_canonical VARCHAR(255) NOT NULL
  , email VARCHAR(255) NOT NULL
  , email_canonical VARCHAR(255) NOT NULL
  , enabled BOOLEAN NOT NULL
  , salt VARCHAR(255) NOT NULL
  , password VARCHAR(255) NOT NULL
  , last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
  , locked BOOLEAN NOT NULL
  , expired BOOLEAN NOT NULL
  , expires_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
  , confirmation_token VARCHAR(255) DEFAULT NULL
  , password_requested_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
  , roles TEXT NOT NULL
  , credentials_expired BOOLEAN NOT NULL
  , credentials_expire_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
  , PRIMARY KEY(id)
);
EOF;

    public function up(Schema $schema)
    {
        $this->addSql('CREATE SEQUENCE seq_user INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(self::CREATE_USERS_SQL);
        $this->addSql('CREATE UNIQUE INDEX UNIQ_USERS_USERNAME_CANONICAL ON users (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_USERS_EMAIL_CANONICAL ON users (email_canonical)');
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP SEQUENCE seq_user');
        $this->addSql('DROP TABLE users');
    }
}
