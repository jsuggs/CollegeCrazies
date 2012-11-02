<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121101221112 extends AbstractMigration
{
    const CREATE_USER_LEAGE_SQL =<<<EOF
CREATE TABLE user_league (
    league_id INT NOT NULL
  , user_id INT NOT NULL
  , PRIMARY KEY(league_id, user_id)
);
EOF;

    public function up(Schema $schema)
    {
        $this->addSql(self::CREATE_USER_LEAGE_SQL);
        $this->addSql('CREATE INDEX IDX_USER_LEAGUE_LEAGUE_ID ON user_league (league_id)');
        $this->addSql('CREATE INDEX IDX_USER_LEAGUE_USER_ID ON user_league (user_id)');
        $this->addSql('ALTER TABLE user_league ADD CONSTRAINT FK_USER_LEAGUE_REF_LEAGUES_LEAGUE_ID FOREIGN KEY (league_id) REFERENCES leagues (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_league ADD CONSTRAINT FK_USER_LEAGUE_REF_USERS_USER_ID FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE user_league');
    }
}
