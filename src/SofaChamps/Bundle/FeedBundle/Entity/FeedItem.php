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
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string", length=16)
 * @ORM\DiscriminatorMap({
 *      "ncaam" = "SofaChamps\Bundle\FeedBundle\Entity\FeedItem\NCAAM",
 * })
 */
abstract class FeedItem
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
