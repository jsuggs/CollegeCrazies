<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20130112133442 extends AbstractMigration
{
    const SQL_QUESTION_CREATE =<<<EOF
CREATE TABLE question_questions (
    id INT NOT NULL
  , text VARCHAR(255) NOT NULL
  , PRIMARY KEY(id)
);
EOF;

    const SQL_QUESTION_CHOICE_CREATE =<<<EOF
CREATE TABLE question_question_choices (
    id INT NOT NULL
  , question_id INT DEFAULT NULL
  , text VARCHAR(255) NOT NULL
  , PRIMARY KEY(id)
);
EOF;

    public function up(Schema $schema)
    {
        $this->addSql('CREATE SEQUENCE seq_question_question INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(self::SQL_QUESTION_CREATE);
        $this->addSql('CREATE SEQUENCE seq_question_question_choice INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(self::SQL_QUESTION_CHOICE_CREATE);
        $this->addSql('CREATE INDEX IDX_4E09963B1E27F6BF ON question_question_choices (question_id)');
        $this->addSql('ALTER TABLE question_question_choices ADD CONSTRAINT FK_4E09963B1E27F6BF FOREIGN KEY (question_id) REFERENCES question_questions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE question_question_choices');
        $this->addSql('DROP SEQUENCE seq_question_question_choice');
        $this->addSql('DROP TABLE question_questions');
        $this->addSql('DROP SEQUENCE seq_question_question');
        //$this->addSql('');
    }
}
