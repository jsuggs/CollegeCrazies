<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20130124104013 extends AbstractMigration
{
    const CREATE_SBC_QUESTIONS_SQL = <<<EOF
CREATE TABLE sbc_questions (
    year INT DEFAULT NULL
  , index INT NOT NULL
  , text VARCHAR(255) NOT NULL
  , PRIMARY KEY(year, index)
);
EOF;

    const CREATE_SBC_QUESTION_CHOICES_SQL =<<<EOF
CREATE TABLE sbc_question_choices (
    id INT NOT NULL
  , year INT NOT NULL
  , index INT NOT NULL
  , text VARCHAR(255) NOT NULL
  , PRIMARY KEY(id)
);
EOF;

    public function up(Schema $schema)
    {
        $this->addSql('CREATE SEQUENCE seq_sbc_question INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(self::CREATE_SBC_QUESTIONS_SQL);
        $this->addSql('CREATE INDEX IDX_SBC_QUESTIONS_YEAR ON sbc_questions (year)');
        $this->addSql('ALTER TABLE sbc_questions ADD CONSTRAINT FK_SBC_QUESTIONS_REF_SBC_CONFIG_YEAR FOREIGN KEY (year) REFERENCES sbc_config (year) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('CREATE SEQUENCE seq_sbc_question_choice INCREMENT BY 1 MINVALUE 1 START 1;');
        $this->addSql(self::CREATE_SBC_QUESTION_CHOICES_SQL);
        $this->addSql('CREATE INDEX IDX_SBC_QUESTION_CHOICES_COMPOSITE_INDEX_YEAR ON sbc_question_choices (index, year)');
        $this->addSql('ALTER TABLE sbc_question_choices ADD CONSTRAINT FK_SBC_QUESTION_CHOICES_REF_SBC_QUESTIONS_INDEX_YEAR FOREIGN KEY (index, year) REFERENCES sbc_questions (index, year) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP SEQUENCE seq_sbc_question_choice');
        $this->addSql('DROP TABLE sbc_question_choices');
        $this->addSql('DROP SEQUENCE seq_sbc_question');
        $this->addSql('DROP TABLE sbc_questions');
    }
}
