<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20131127221956 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('DROP INDEX UNIQ_GAMES_HOMETEAM_ID');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_GAMES_HOMETEAM_ID ON games (season, homeTeam_id)');
        $this->addSql('DROP INDEX UNIQ_GAMES_AWAYTEAM_ID');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_GAMES_AWAYTEAM_ID ON games (season, awayTeam_id)');
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP INDEX UNIQ_GAMES_HOMETEAM_ID');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_GAMES_HOMETEAM_ID ON games (homeTeam_id)');
        $this->addSql('DROP INDEX UNIQ_GAMES_AWAYTEAM_ID');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_GAMES_AWAYTEAM_ID ON games (awayTeam_id)');
    }
}
