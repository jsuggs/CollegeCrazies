<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20141219061931 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE bp_seasons ADD picksLockAt TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE bp_seasons ADD locked BOOLEAN NOT NULL DEFAULT false');
        $this->addSql('ALTER TABLE bp_seasons ADD gamePoints INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bp_seasons ADD championshipPoints INT DEFAULT NULL');

        $this->addSql("UPDATE bp_seasons SET picksLockAt = '2012-12-15 13:00:00' WHERE season = '2012'");
        $this->addSql("UPDATE bp_seasons SET picksLockAt = '2013-12-21 14:00:00' WHERE season = '2013'");
        $this->addSql("UPDATE bp_seasons SET picksLockAt = '2014-12-20 11:00:00' WHERE season = '2014'");

        $this->addSql("UPDATE bp_seasons SET locked = true WHERE season IN (2012, 2013)");

        $this->addSql("UPDATE bp_seasons SET gamePoints = 630 WHERE season = '2012'");
        $this->addSql("UPDATE bp_seasons SET gamePoints = 630 WHERE season = '2013'");
        $this->addSql("UPDATE bp_seasons SET gamePoints = 741 WHERE season = '2014'");

        $this->addSql("UPDATE bp_seasons SET championshipPoints = 24 WHERE season = '2014'");

        $this->addSql('ALTER TABLE bp_seasons ALTER gamePoints SET NOT NULL');
        $this->addSql('ALTER TABLE bp_seasons ALTER picksLockAt SET NOT NULL');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE bp_seasons DROP picksLockAt');
        $this->addSql('ALTER TABLE bp_seasons DROP locked');
        $this->addSql('ALTER TABLE bp_seasons DROP gamePoints');
        $this->addSql('ALTER TABLE bp_seasons DROP championshipPoints');
    }
}
