<?php

namespace SofaChamps\Bundle\FeedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\User;

/**
 * A vote for a feed item
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="feed_item_votes"
 * )
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string", length=16)
 * @ORM\DiscriminatorMap({
 *      "up" = "SofaChamps\Bundle\FeedBundle\Entity\Vote\Up",
 * })
 */
abstract class Vote
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_feed_items", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="FeedItemHistory", mappedBy="feedItem")
     */
    protected $feedItemHistory;

    public function getId()
    {
        return $this->id;
    }
}
