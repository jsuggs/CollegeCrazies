<?php

namespace CollegeCrazies\Bundle\MainBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

class GameTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (Game) to a string (id).
     *
     * @param  Game|null $game
     * @return string
     */
    public function transform($game)
    {
        if (null === $game) {
            return "";
        }

        return $game->getId();
    }

    /**
     * Transforms a string (id) to an object (Game).
     *
     * @param  string $id
     * @return Game|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $game = $this->om
            ->getRepository('CollegeCraziesMainBundle:Game')
            ->findOneBy(array('id' => $id));

        if (!$game) {
            throw new TransformationFailedException(sprintf('An game with id "%s" does not exist!', $id));
        }

        return $game;
    }
}
