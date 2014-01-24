<?php

namespace SofaChamps\Bundle\SquaresBundle\Tests\Listener\Payout;

use SofaChamps\Bundle\CoreBundle\Tests\SofaChampsWebTest;
use SofaChamps\Bundle\SquaresBundle\Entity\Payout;

/**
 * @group functional
 */
class PayoutWinnerListenerTest extends SofaChampsWebTest
{
    protected $user;
    protected $jsuggsUser;
    protected $game;

    protected function setUp()
    {
        parent::setUp();

        $this->loadFixtures(array(
            'SofaChamps\Bundle\CoreBundle\DataFixtures\ORM\TestUsers',
            'SofaChamps\Bundle\SquaresBundle\DataFixtures\ORM\Squares',
        ));

        $this->user = $this->getEntityManager()->getRepository('SofaChampsCoreBundle:User')->findOneByUsername('user');
        $this->jsuggsUser = $this->getEntityManager()->getRepository('SofaChampsCoreBundle:User')->findOneByUsername('jsuggs');
        $this->game = $this->getEntityManager()->getRepository('SofaChampsSquaresBundle:Game')->findOneByName('Test');
    }

    public function testLogCreated()
    {
        $this->assertEquals(1, $this->game->getLogs()->count());
        $this->assertEquals(100, $this->game->getClaimedSquares()->count());
        $userPlayer = $this->game->getPlayerForUser($this->user);

        // Create a payout for the game
        $payout = new Payout($this->game, 1);
        $payout->setDescription('Test Payout');
        $payout->setPercentage(10);

        $this->getEntityManager()->persist($payout);
        $this->getEntityManager()->flush();

        // Set the result
        $payout->setHomeTeamResult(0);
        $payout->setAwayTeamResult(0);
        $this->getEntityManager()->flush();

        $payoutId = $payout->getId();

        // Re-query from the db
        $this->getEntityManager()->clear();
        $payout = $this->getEntityManager()->getRepository('SofaChampsSquaresBundle:Payout')->findOneBy(array(
            'id' => $payoutId,
            'seq' => 1,
        ));

        $this->assertEquals($userPlayer->getId(), $payout->getWinner()->getId());
        $this->assertEquals(2, $this->game->getLogs()->count());
    }
}
