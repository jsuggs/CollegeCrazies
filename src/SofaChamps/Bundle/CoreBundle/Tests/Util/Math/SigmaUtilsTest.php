<?php

namespace SofaChamps\Bundle\CoreBundle\Tests\Util\Math;

use SofaChamps\Bundle\CoreBundle\Util\Math\SigmaUtils;

class SigmaUtilsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider summationData
     */
    public function testSummation($base, $summation)
    {
        $this->assertEquals($summation, SigmaUtils::summation($base));
    }

    public function summationData()
    {
        return array(
            array(-5, 15),
            array(-4, 10),
            array(-3, 6),
            array(-2, 3),
            array(-1, 1),
            array(0,  0),
            array(1,  1),
            array(2,  3),
            array(3,  6),
            array(4,  10),
            array(5,  15),
        );
    }
}
