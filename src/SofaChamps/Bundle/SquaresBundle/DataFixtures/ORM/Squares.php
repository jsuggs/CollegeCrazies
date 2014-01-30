<?php

namespace SofaChamps\Bundle\SquaresBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SofaChamps\Bundle\SquaresBundle\Entity\Square;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Squares extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $gameManager = $this->container->get('sofachamps.squares.game_manager');
        $playerManager = $this->container->get('sofachamps.squares.player_manager');

        $game = $gameManager->createGame($this->getReference('user-user'), false);
        $game->setName('Test');
        $game->setHomeTeam('Home Team');
        $game->setAwayTeam('Away Team');
        $game->setCostPerSquare(50);

        $userPlayer = $game->getPlayerForUser($this->getReference('user-user'));
        $jsuggsPlayer = $playerManager->createPlayer($this->getReference('user-jsuggs'), $game, false);

        // Claim some squares
        foreach (range(0, 9) as $row) {
            foreach (range(0, 9) as $col) {
                $user = ($row % 2 == 0)
                    ? $userPlayer
                    : $jsuggsPlayer;

                $square = $game->getSquare($row, $col);
                $square->setPlayer($user);
            }
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 100;
    }
}
