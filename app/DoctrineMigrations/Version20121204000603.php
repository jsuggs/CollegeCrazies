<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121204000603 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE games SET spread = 9.5  WHERE awayteam_id = 'ALA';");
        $this->addSql("UPDATE games SET spread = 5    WHERE awayteam_id = 'ARST';");
        $this->addSql("UPDATE games SET spread = 3.5  WHERE awayteam_id = 'MISS';");
        $this->addSql("UPDATE games SET spread = -4.5 WHERE awayteam_id = 'OKLA';");
        $this->addSql("UPDATE games SET spread = -9   WHERE awayteam_id = 'KSST';");
        $this->addSql("UPDATE games SET spread = 14   WHERE awayteam_id = 'FLA';");
        $this->addSql("UPDATE games SET spread = 13.5 WHERE awayteam_id = 'FLST';");
        $this->addSql("UPDATE games SET spread = 6.5  WHERE awayteam_id = 'STAN';");
        $this->addSql("UPDATE games SET spread = -10  WHERE awayteam_id = 'NEB';");
        $this->addSql("UPDATE games SET spread = -4   WHERE awayteam_id = 'MICH';");
        $this->addSql("UPDATE games SET spread = 16.5 WHERE awayteam_id = 'OKST';");
        $this->addSql("UPDATE games SET spread = -2   WHERE awayteam_id = 'NW';");
        $this->addSql("UPDATE games SET spread = -3.5 WHERE awayteam_id = 'CLEM';");
        $this->addSql("UPDATE games SET spread = 0    WHERE awayteam_id = 'TULS';");
        $this->addSql("UPDATE games SET spread = 0    WHERE awayteam_id = 'GT';");
        $this->addSql("UPDATE games SET spread = 6.5  WHERE awayteam_id = 'VAND';");
        $this->addSql("UPDATE games SET spread = -2.5 WHERE awayteam_id = 'MIST';");
        $this->addSql("UPDATE games SET spread = 1.5  WHERE awayteam_id = 'ORST';");
        $this->addSql("UPDATE games SET spread = 0    WHERE awayteam_id = 'AZST';");
        $this->addSql("UPDATE games SET spread = -4   WHERE awayteam_id = 'SYRA';");
        $this->addSql("UPDATE games SET spread = 1    WHERE awayteam_id = 'AFA';");
        $this->addSql("UPDATE games SET spread = 12.5 WHERE awayteam_id = 'TEXT';");
        $this->addSql("UPDATE games SET spread = 2.5  WHERE awayteam_id = 'VT';");
        $this->addSql("UPDATE games SET spread = 7    WHERE awayteam_id = 'ULM';");
        $this->addSql("UPDATE games SET spread = 2    WHERE awayteam_id = 'UCLA';");
        $this->addSql("UPDATE games SET spread = -7.5 WHERE awayteam_id = 'DUKE';");
        $this->addSql("UPDATE games SET spread = -7   WHERE awayteam_id = 'BGSU';");
        $this->addSql("UPDATE games SET spread = -6   WHERE awayteam_id = 'CEMI';");
        $this->addSql("UPDATE games SET spread = -12  WHERE awayteam_id = 'SMU';");
        $this->addSql("UPDATE games SET spread = 5    WHERE awayteam_id = 'BOST';");
        $this->addSql("UPDATE games SET spread = 5.5  WHERE awayteam_id = 'ULL';");
        $this->addSql("UPDATE games SET spread = -8   WHERE awayteam_id = 'BAST';");
        $this->addSql("UPDATE games SET spread = -3   WHERE awayteam_id = 'SDSU';");
        $this->addSql("UPDATE games SET spread = 9.5  WHERE awayteam_id = 'UTST';");
        $this->addSql("UPDATE games SET spread = 9.5  WHERE awayteam_id = 'ARIZ';");
    }

    public function down(Schema $schema)
    {
        $this->addSql('UPDATE games set spread = 0');
    }
}
