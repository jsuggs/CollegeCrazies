<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20130126155813 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $questions = array(
            array(
                'year' => 2013,
                'index' => 1,
                'text' => 'What team will make the 1st Timeout?',
                'choices' => array(
                    'Ravens',
                    '49ers',
                )
            ),
            array(
                'year' => 2013,
                'index' => 2,
                'text' => 'Will Beyonce be jonied by Jay Z on stage during the Half Time Show?',
                'choices' => array(
                    'No',
                    'Yes',
                )
            ),
            array(
                'year' => 2013,
                'index' => 3,
                'text' => 'What Color will the Gatorade (or liquid) be that is dumped on the Head Coach of the Winning Super Bowl Team?',
                'choices' => array(
                    'Clear',
                    'Orange',
                    'Yellow',
                    'Blue',
                    'Red',
                    'Green',
                    'Other',
                )
            ),
            array(
                'year' => 2013,
                'index' => 4,
                'text' => 'What team will be the winner of the coin toss?',
                'choices' => array(
                    'Ravens',
                    '49ers',
                )
            ),
            array(
                'year' => 2013,
                'index' => 5,
                'text' => 'Will any player get a penalty for excessive celebration in the game?',
                'choices' => array(
                    'No',
                    'Yes',
                )
            ),
            array(
                'year' => 2013,
                'index' => 6,
                'text' => 'Will Alicia Keys take over or under 2 minutes and 15 second?',
                'choices' => array(
                    'Under',
                    'Over',
                )
            ),
            array(
                'year' => 2013,
                'index' => 7,
                'text' => 'Will Beyonce\'\'s hair be Curly/Crimped or Straight at the beginning of the Super Bowl Halftime show?',
                'choices' => array(
                    'Straight',
                    'Curly/Crimped',
                )
            ),
            array(
                'year' => 2013,
                'index' => 8,
                'text' => 'If Ray Lewis is interviewed on TV after the game on the field or in the locker room how many times will he mention "God/Lord". Live pictures only.',
                'choices' => array(
                    '3 or more',
                    'Less than 3',
                )
            ),
        );

        foreach ($questions as $question) {
            $this->addSql(sprintf("INSERT into sbc_questions (year, index, text) VALUES(%d, %d, '%s')", $question['year'], $question['index'], $question['text']));
            foreach ($question['choices'] as $choice) {
                $this->addSql(sprintf("INSERT into sbc_question_choices (id, year, index, text) VALUES(nextval('seq_sbc_question_choice'), %d, %d, '%s')", $question['year'], $question['index'], $choice));
            }
        }
    }

    public function down(Schema $schema)
    {
        $this->addSql('TRUNCATE sbc_questions CASCADE');
    }
}
