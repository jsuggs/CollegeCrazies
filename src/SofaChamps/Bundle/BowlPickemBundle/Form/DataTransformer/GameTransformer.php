<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * GameTransformer
 *
 * @DI\Service("sofachamps.bp.transformer.game")
 */
class GameTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @DI\InjectParams({
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager")
     * })
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
            ->getRepository('SofaChampsBowlPickemBundle:Game')
            ->findOneBy(array('id' => $id));

        if (!$game) {
            throw new TransformationFailedException(sprintf('An game with id "%s" does not exist!', $id));
        }

        return $game;
    }
}
