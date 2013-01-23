<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Tests\Util;

use SofaChamps\Bundle\BowlPickemBundle\Util\TextManipulator;

class TextManipulatorTest extends \PHPUnit_Framework_TestCase
{
    private $manipulator;

    protected function setUp()
    {
        $this->manipulator = new TextManipulator();
    }

    /**
     * @dataProvider testStrings
     */
    public function testTruncateText($text, $maxLen, $additionalText, $expectedResult)
    {
        if ($additionalText) {
            $this->assertEquals($expectedResult, $this->manipulator->truncateText($text, $maxLen, $additionalText));
        } else {
            $this->assertEquals($expectedResult, $this->manipulator->truncateText($text, $maxLen));
        }
    }

    public function testStrings()
    {
        return array(
            array('test', 5, null, 'test'),
            array('this is a test', 6, null, 'this is...'),
            array('a ab abc abcd abcde abcdef', 15, null, 'a ab abc abcd...'),
            array('test', 5, 'will not show up', 'test'),
            array('this is a test', 6, ',,,', 'this is,,,'),
        );
    }
}
