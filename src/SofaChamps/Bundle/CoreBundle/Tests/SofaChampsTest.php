<?php

namespace SofaChamps\Bundle\CoreBundle\Tests;

use Faker\Factory;

class SofaChampsTest extends \PHPUnit_Framework_TestCase
{
    private $faker;

    protected function setUp()
    {
        parent::setUp();

        $this->faker = Factory::create();
    }

    public function buildMock($class)
    {
        return $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getContainer()
    {
        return $this->getKernel()->getContainer();
    }

    protected function getFaker()
    {
        return $this->faker;
    }
}
