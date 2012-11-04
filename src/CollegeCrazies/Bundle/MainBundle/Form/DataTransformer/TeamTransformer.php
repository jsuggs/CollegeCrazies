<?php

namespace CollegeCrazies\Bundle\MainBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

class TeamTransformer implements DataTransformerInterface
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
            ->getRepository('CollegeCraziesMainBundle:Team')
            ->findOneBy(array('id' => $id));

        if (!$team) {
            throw new TransformationFailedException(sprintf('An team with id "%s" does not exist!', $id));
        }

        return $team;
    }
}
