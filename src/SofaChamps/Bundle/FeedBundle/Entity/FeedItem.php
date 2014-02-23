<?php

namespace SofaChamps\Bundle\FeedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\User;

/**
 * A feed item
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="feed_items"
 * )
 */
class FeedItem
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
