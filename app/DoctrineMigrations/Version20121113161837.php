<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121113161837 extends AbstractMigration
{
    const CREATE_LEAGUE_COMMISSIONERS_SQL =<<<EOF
CREATE TABLE league_commissioners (
    league_id INT NOT NULL
  , user_id INT NOT NULL
  , PRIMARY KEY(league_id, user_id)
);
EOF;

    public function up(Schema $schema)
    {
        $this->addSql(self::CREATE_LEAGUE_COMMISSIONERS_SQL);
        $this->addSql('CREATE INDEX IDX_LEAGUE_COMMISSIONERS_LEAGUE_ID ON league_commissioners (league_id)');
        $this->addSql('CREATE INDEX IDX_LEAGUE_COMMISSIONERS_USER_ID ON league_commissioners (user_id)');
        $this->addSql('ALTER TABLE league_commissioners ADD CONSTRAINT FK_LEAGUE_COMMISSIONERS_REF_LEAGUES_LEAGUE_ID FOREIGN KEY (league_id) REFERENCES leagues (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE league_commissioners ADD CONSTRAINT FK_LEAGUE_COMMISSIONERS_REF_USERS_USER_ID FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;');
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE league_commissioners');
    }
}
