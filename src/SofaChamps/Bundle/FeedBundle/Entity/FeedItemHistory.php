<?php

namespace SofaChamps\Bundle\FeedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\User;

/**
 * Any history for a feed item
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="feed_item_history"
 * )
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string", length=16)
 * @ORM\DiscriminatorMap({
 *      "lock" = "SofaChamps\Bundle\FeedBundle\Entity\FeedItemHistory\Lock",
 *      "unlock" = "SofaChamps\Bundle\FeedBundle\Entity\FeedItemHistory\Unlock",
 * })
 */
abstract class FeedItemHistory
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_feed_history", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="FeedItem", inversedBy="feedItemHistory")
     */
    protected $feedItem;

    /**
     * @ORM\ManyToOne(targetEntity="SofaChamps\Bundle\CoreBundle\Entity\User", inversedBy="feedItemHistory")
     */
    protected $user;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    public function __construct(FeedItem $feedItem, User $user)
    {
        $this->feedItem = $feedItem;
        $this->user = $user;
        $this->createdAt = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFeedItem()
    {
        return $this->feedItem;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
