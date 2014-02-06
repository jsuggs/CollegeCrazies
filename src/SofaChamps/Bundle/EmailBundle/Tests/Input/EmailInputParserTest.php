<?php

namespace SofaChamps\Bundle\EmailBundle\Tests\Email;

use SofaChamps\Bundle\CoreBundle\Tests\SofaChampsTest;
use SofaChamps\Bundle\EmailBundle\Input\EmailInputParser;

class EmailInputParserTest extends SofaChampsTest
{
    protected $parser;

    protected function setUp()
    {
        parent::setUp();

        $this->parser = new EmailInputParser();
    }

    /**
     * @dataProvider inputProvider
     */
    public function testParseEmails($text, $expectedResult)
    {
        $result = $this->parser->parseEmails($text);
        $this->assertEqualsArrays($expectedResult, $result);
    }

    public function inputProvider()
    {
        return array(
            array('test@test.com', array('test@test.com')),
            array('test@test.com, x@x.com', array('test@test.com', 'x@x.com')),
            array('test@test.com x@x.com', array('test@test.com', 'x@x.com')),
            array('test@test.com x@x.com, y@y.com', array('test@test.com', 'x@x.com', 'y@y.com')),
            array("test@test.com x@x.com, y@y.com\nz@z.com", array('test@test.com', 'x@x.com', 'y@y.com', 'z@z.com')),
            array("test@test.com x@x.com, y@y.com\nz@z.com\ninvalid", array('test@test.com', 'x@x.com', 'y@y.com', 'z@z.com')),
            //array("\"test@test.com\"\n x@x.com, \"y@y.com\"\nz@z.com", array('test@test.com', 'x@x.com', 'y@y.com', 'z@z.com')),
        );
    }

    protected function assertEqualsArrays($expected, $actual) {
        sort($expected);
        sort($actual);

        $this->assertEquals($expected, $actual);
    }
}
