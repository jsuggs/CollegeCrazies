<?php

namespace SofaChamps\Bundle\FeedBundle\Entity\Vote;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\FeedBundle\Entity\Vote;

/**
 * An up vote for a feed item
 *
 * @ORM\Entity
 */
class Up extends Vote
{
}
