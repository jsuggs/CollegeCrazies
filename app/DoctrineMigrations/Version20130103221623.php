<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20130103221623 extends AbstractMigration
{
    const SQL_SBC_PICKS_CREATE =<<<EOF
CREATE TABLE sbc_picks (
    id INT NOT NULL
  , user_id INT DEFAULT NULL
  , homeTeamFinalScore INT NOT NULL
  , awayTeamFinalScore INT NOT NULL
  , PRIMARY KEY(id)
);
EOF;
    public function up(Schema $schema)
    {
        $this->addSql('CREATE SEQUENCE seq_sbc_pick INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(self::SQL_SBC_PICKS_CREATE);
        $this->addSql('CREATE INDEX IDX_40071FF7A76ED395 ON sbc_picks (user_id)');
        $this->addSql('ALTER TABLE sbc_picks ADD CONSTRAINT FK_40071FF7A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP SEQUENCE seq_sbc_pick');
        $this->addSql('DROP TABLE sbc_picks');
    }
}
