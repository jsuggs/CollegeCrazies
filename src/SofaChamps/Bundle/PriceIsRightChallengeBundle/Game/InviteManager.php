<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Game;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\EmailBundle\Email\EmailSenderInterface;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Game;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Invite;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Portfolio\PortfolioManager;

/**
 * @DI\Service("sofachamps.pirc.invite_manager")
 */
class InviteManager
{
    const COOKIE_NAME = 'pirc_invite_game';

    private $om;
    private $portfolioManager;
    private $dispatcher;

    /**
     * @DI\InjectParams({
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager"),
     *      "portfolioManager" = @DI\Inject("sofachamps.pirc.portfolio_manager"),
     *      "emailSender" = @DI\Inject("sofachamps.email.sender"),
     * })
     */
    public function __construct(ObjectManager $om, PortfolioManager $portfolioManager, EmailSenderInterface $emailSender)
    {
        $this->om = $om;
        $this->portfolioManager = $portfolioManager;
        $this->emailSender = $emailSender;
    }

    public function sendInvitesToEmails(Game $game, User $fromUser, array $emails)
    {
        foreach ($emails as $email) {
            $user = $this->findUserByEmail($email);

            if ($user) {
                $this->portfolioManager->createPortfolio($game, $user);
            }

            $this->sendInviteEmail($game, $fromUser, $email);
        }
    }

    protected function sendInviteEmail(Game $game, User $fromUser, $email)
    {
        $subjectLine = sprintf('Invitation to play SofaChamps Price Is Right Challenge - Game: %s', $game->getName());

        $this->emailSender->sendToEmail($email, 'PriceIsRightChallenge:invite', $subjectLine, array(
            'fromUser' => $fromUser,
            'game' => $game,
            'from' => array($fromUser->getEmail() => $fromUser->getUsername()),
        ));

        $invite = new Invite($fromUser, $game, $email);
        $this->om->persist($invite);
    }

    private function findUserByEmail($email)
    {
        return $this->om->getRepository('SofaChampsCoreBundle:User')->findOneByEmail($email);
    }
}
