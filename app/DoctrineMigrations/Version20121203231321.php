<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20121203231321 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/aab.gif' WHERE id = 'AFA';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/aac.gif' WHERE id = 'AKR';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/aad_4.gif' WHERE id = 'ALA';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/aal.gif' WHERE id = 'ARIZ';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/asu-2w.jpg' WHERE id = 'AZST';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/aan_3.gif' WHERE id = 'ARK';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/aap_2.jpg' WHERE id = 'ARST';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/aaq_1.gif' WHERE id = 'ARMY';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/aar.gif' WHERE id = 'AUB';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/bba.gif' WHERE id = 'BAST';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/bbb.gif' WHERE id = 'BAY';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/bbe.gif' WHERE id = 'BOST';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/bbf.gif' WHERE id = 'BC';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/bowling_green/logo_update/50x50w.gif' WHERE id = 'BGSU';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/bbi.gif' WHERE id = 'BYU';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/bbp_2.gif' WHERE id = 'BUF';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ccd_2.gif' WHERE id = 'CAL';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ccg.gif' WHERE id = 'CEMI';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ccj_50x50w_2.jpg' WHERE id = 'CINC';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ccl.gif' WHERE id = 'CLEM';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/cbl_1.gif' WHERE id = 'COLO';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/cco.gif' WHERE id = 'COST';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ccq.gif' WHERE id = 'CONN';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ddf.gif' WHERE id = 'DUKE';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/eea.gif' WHERE id = 'ECU';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/eef.gif' WHERE id = 'EMU';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/flaa-w.jpg' WHERE id = 'FAU';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ffa.gif' WHERE id = 'FLA';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/fli_1.gif' WHERE id = 'FIU';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ffc.gif' WHERE id = 'FLST';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ffe.gif' WHERE id = 'FRST';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ggb.gif' WHERE id = 'UGA';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ggc.gif' WHERE id = 'GT';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/hhc.gif' WHERE id = 'HAW';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/hhe2.gif' WHERE id = 'HOU';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/iia.gif' WHERE id = 'IDA';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/iic2.gif' WHERE id = 'ILL';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/iig.gif' WHERE id = 'IOWA';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/iih_2.jpg' WHERE id = 'IAST';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/kab_3.gif' WHERE id = 'KSST';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/kkc.gif' WHERE id = 'KEST';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/laf-2.jpg' WHERE id = 'ULL';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/nnb2.gif' WHERE id = 'ULM';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/llg_2.gif' WHERE id = 'LT';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/llh2.gif' WHERE id = 'LOUI';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us//sp/v/ncaaf/teams/1/50x50w/lli_2.gif' WHERE id = 'LSU';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/mmc.gif' WHERE id = 'MARS';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/mmk.gif' WHERE id = 'MICH';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/mml.gif' WHERE id = 'MIST';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/mmn_1.gif' WHERE id = 'MINN';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/mmq_2.gif' WHERE id = 'MSST';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/mms_2.gif' WHERE id = 'MO';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/nnn_2.gif' WHERE id = 'NCST';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/nna.gif' WHERE id = 'NAVY';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/nnd.gif' WHERE id = 'NEB';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/nnf_2.gif' WHERE id = 'NEV';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/mls/teams/50x50w/nc-2.gif' WHERE id = 'UNC';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/nns.gif' WHERE id = 'NIU';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/nnv.gif' WHERE id = 'NW';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/nnx.gif' WHERE id = 'ND';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ooa.gif' WHERE id = 'OHIO';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/oob.gif' WHERE id = 'OHST';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ooc.gif' WHERE id = 'OKLA';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ood.gif' WHERE id = 'OKST';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/mmo_2.gif' WHERE id = 'MISS';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ooe.gif' WHERE id = 'ORE';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/oof.gif' WHERE id = 'ORST';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ppb_2.gif' WHERE id = 'PSU';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ppd_1.gif' WHERE id = 'PITT';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ppj.gif' WHERE id = 'PURD';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/rrb2.gif' WHERE id = 'RICE';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/3/50x50w/rrd_50x50w_2.jpg' WHERE id = 'RUT';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ssb.gif' WHERE id = 'SDSU';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ssc.gif' WHERE id = 'SJSU';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ssh2.gif' WHERE id = 'SMU';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ssi.gif' WHERE id = 'SCAR';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/sso_2.gif' WHERE id = 'USM';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/sss3.gif' WHERE id = 'STAN';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/syc-2.jpg' WHERE id = 'SYRA';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/tta.gif' WHERE id = 'TCU';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ttb.gif' WHERE id = 'TEM';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/tth.gif' WHERE id = 'TEX';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ttj.gif' WHERE id = 'TAMU';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/tto.gif' WHERE id = 'TEXT';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ttp.gif' WHERE id = 'TOL';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/ttt.gif' WHERE id = 'TULS';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaab/teams/1/50x50w/ccf2.gif' WHERE id = 'UCF';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/uua.gif' WHERE id = 'UCLA';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/uub.gif' WHERE id = 'USC';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/uuc.gif' WHERE id = 'UTAH';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/uud.gif' WHERE id = 'UTST';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/vva_1.gif' WHERE id = 'VAND';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/vvb_2.gif' WHERE id = 'UVA';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/vvd.gif' WHERE id = 'VT';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/wwa.gif' WHERE id = 'WAKE';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/was-2.jpg' WHERE id = 'WASH';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/wwh.gif' WHERE id = 'WVU';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/wwk.gif' WHERE id = 'WEKY';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/wwl.gif' WHERE id = 'WMU';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/wwo.gif' WHERE id = 'WIS';");
        $this->addSql("UPDATE teams SET thumbnail = 'http://l.yimg.com/a/i/us/sp/v/ncaaf/teams/1/50x50w/wwq.gif' WHERE id = 'WYO';");
    }

    public function down(Schema $schema)
    {
        $this->addSql("UPDATE teams SET thumbnail = ''");
    }
}
