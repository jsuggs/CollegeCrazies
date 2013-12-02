<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20131201223030 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE games ADD tiebreakerPriority SMALLINT");
        $this->addSql("CREATE UNIQUE INDEX uniq_games_season_tiebreaker_priority ON games (season, tiebreakerPriority)");
    }

    public function down(Schema $schema)
    {
        $this->addSql("DROP INDEX uniq_games_season_tiebreaker_priority");
        $this->addSql("ALTER TABLE games DROP tiebreakerPriority");
    }
}
