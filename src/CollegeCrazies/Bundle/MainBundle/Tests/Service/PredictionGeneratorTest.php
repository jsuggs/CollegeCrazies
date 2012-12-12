<?php

namespace CollegeCrazies\Bundle\MainBundle\Tests\Service;

use CollegeCrazies\Bundle\MainBundle\Service\PredictionGenerator;

class PredictionGeneratorTest extends \PHPUnit_Framework_TestCase
{
    protected $om;

    protected function setUp()
    {
        $this->om = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->generator = new PredictionGenerator($this->om);
    }

    /**
     * @dataProvider percentageResults
     */
    public function testGetHomeTeamWinPercentage($spread, $winPercent)
    {
        $this->assertEquals($winPercent, $this->generator->getHomeTeamWinPercentage($spread));
    }

    public function percentageResults()
    {
        return array(
            array( 25,   0.05),
            array(10.5,  0.23675),
            array( 10,   0.2474),
            array(  2,   0.4586),
            array(  1,   0.4904),
            array(  0,   0.5),
            array( -1,   0.5096),
            array( -2,   0.5414),
            array(-10,   0.7526),
            array(-10.5, 0.76325),
            array(-25,   0.95),
        );
    }
}

