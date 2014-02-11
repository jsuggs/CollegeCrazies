<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * TeamTransformer
 *
 * @DI\Service("sofachamps.bp.transformer.team")
 */
class TeamTransformer implements DataTransformerInterface
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
     * Transforms an object (Team) to a string (id).
     *
     * @param  Team|null $team
     * @return string
     */
    public function transform($team)
    {
        if (null === $team) {
            return "";
        }

        return $team->getId();
    }

    /**
     * Transforms a string (id) to an object (Team).
     *
     * @param  string $id
     * @return Team|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $team = $this->om
            ->getRepository('SofaChampsNCAABundle:Team')
            ->findOneBy(array('id' => $id));

        if (!$team) {
            throw new TransformationFailedException(sprintf('An team with id "%s" does not exist!', $id));
        }

        return $team;
    }
}
