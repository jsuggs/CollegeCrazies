<?php

namespace SofaChamps\Bundle\FeedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\User;

/**
 * The feed for a user.
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="feed_user"
 * )
 */
class UserFeed
{
    /**
     * @ORM\ManyToOne
     */
    protected $user;

    /**
     * @ORM\ManyToOne
     */
    protected $feedItem;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    public function __construct(User $user, FeedItem $feedItem)
    {
        $this->user = $user;
        $this->feedItem = $feedItem;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getFeedItem()
    {
        return $this->feedItem;
    }
}

