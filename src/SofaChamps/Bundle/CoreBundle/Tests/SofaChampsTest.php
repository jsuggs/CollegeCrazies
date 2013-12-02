<?php

namespace SofaChamps\Bundle\CoreBundle\Tests;

class SofaChampsTest extends \PHPUnit_Framework_TestCase
{
    public function buildMock($class)
    {
        return $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
