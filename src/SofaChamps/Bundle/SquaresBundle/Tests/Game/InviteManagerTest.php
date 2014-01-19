<?php

namespace SofaChamps\Bundle\SquaresBundle\Tests\Game;

use SofaChamps\Bundle\SquaresBundle\Entity\Game;
use SofaChamps\Bundle\SquaresBundle\Game\InviteManager;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\CoreBundle\Tests\SofaChampsTest;

class InviteManagerTest extends SofaChampsTest
{
    protected $om;
    protected $playerManager;
    protected $emailSender;
    protected $manager;

    protected function setUp()
    {
        parent::setUp();

        $this->om = $this->buildMock('Doctrine\Common\Persistence\ObjectManager');
        $this->playerManager = $this->buildMock('SofaChamps\Bundle\SquaresBundle\Game\PlayerManager');
        $this->emailSender = $this->buildMock('SofaChamps\Bundle\EmailBundle\Email\EmailSenderInterface');

        $this->manager = new InviteManager($this->om, $this->playerManager, $this->emailSender);
    }

    public function testSendInvitesToEmails()
    {
        $userEmail = $this->getFaker()->email();
        $nonUserEmail = $this->getFaker()->email();

        $user = new User();
        $fromUser = new User();
        $game = new Game($user);

        $userRepo = $this->getMockBuilder('SofaChamps\Bundle\CoreBundle\Entity\UserRepository')
            ->setMethods(array('findOneByEmail'))
            ->disableOriginalConstructor()
            ->getMock();

        $this->om->expects($this->exactly(2))
            ->method('getRepository')
            ->with('SofaChampsCoreBundle:User')
            ->will($this->returnValue($userRepo));

        $userRepo->expects($this->at(0))
            ->method('findOneByEmail')
            ->with($userEmail)
            ->will($this->returnValue($user));

        $userRepo->expects($this->at(1))
            ->method('findOneByEmail')
            ->with($nonUserEmail)
            ->will($this->returnValue(null));

        $this->playerManager->expects($this->once())
            ->method('createPlayer')
            ->with($user, $game);

        $this->emailSender->expects($this->exactly(2))
            ->method('sendToEmail');

        $game = $this->manager->sendInvitesToEmails($game, $fromUser, array($userEmail, $nonUserEmail));

    }
}


