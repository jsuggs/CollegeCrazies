<?php

namespace SofaChamps\Bundle\SquaresBundle\Game;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\EmailBundle\Email\EmailSenderInterface;
use SofaChamps\Bundle\SquaresBundle\Entity\Game;
use SofaChamps\Bundle\SquaresBundle\Entity\Invite;

/**
 * @DI\Service("sofachamps.squares.invite_manager")
 */
class InviteManager
{
    private $om;
    private $playerManager;
    private $emailSender;
    private $logManager;

    /**
     * @DI\InjectParams({
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager"),
     *      "playerManager" = @DI\Inject("sofachamps.squares.player_manager"),
     *      "emailSender" = @DI\Inject("sofachamps.email.sender"),
     *      "logManager" = @DI\Inject("sofachamps.squares.log_manager"),
     * })
     */
    public function __construct(ObjectManager $om, PlayerManager $playerManager, EmailSenderInterface $emailSender, LogManager $logManager)
    {
        $this->om = $om;
        $this->playerManager = $playerManager;
        $this->emailSender = $emailSender;
        $this->logManager = $logManager;
    }

    public function sendInvitesToEmails(Game $game, User $fromUser, array $emails)
    {
        foreach ($emails as $email) {
            $user = $this->findUserByEmail($email);

            if ($user) {
                $this->playerManager->createPlayer($user, $game);
            }

            $this->sendInviteEmail($game, $fromUser, $email);
        }
    }

    protected function sendInviteEmail(Game $game, User $fromUser, $email)
    {
        $subjectLine = sprintf('Invitation to play SofaChamps Squares - Game: %s', $game->getName());

        $this->emailSender->sendToEmail($email, 'Squares:invite', $subjectLine, array(
            'fromUser' => $fromUser,
            'game' => $game,
            'from' => array($fromUser->getEmail() => $fromUser->getUsername()),
        ));

        $invite = new Invite($fromUser, $email);
        $this->om->persist($invite);

        $this->logManager->createLog($game, sprintf(
            'Invite sent to %s',
            $email
        ));
    }

    private function findUserByEmail($email)
    {
        return $this->om->getRepository('SofaChampsCoreBundle:User')->findOneByEmail($email);
    }
}
