<?php

namespace SofaChamps\Bundle\CoreBundle\Tests\Referral;

use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\CoreBundle\Referral\ReferralManager;
use SofaChamps\Bundle\CoreBundle\Tests\SofaChampsTest;

class ReferralManagerTest extends SofaChampsTest
{
    private $manager;

    protected function setUp()
    {
        $this->manager = new ReferralManager();
    }

    public function testAddReferrToUser()
    {
        $user = new User();

        $this->assertNull($user->getReferrer());

        $referrer = new User();
        $this->manager->addReferrerToUser($user, $referrer);

        $this->assertEquals($referrer, $user->getReferrer());

        $tooLate = new User();

        $this->manager->addReferrerToUser($user, $tooLate);

        $this->assertEquals($referrer, $user->getReferrer());
    }
}
