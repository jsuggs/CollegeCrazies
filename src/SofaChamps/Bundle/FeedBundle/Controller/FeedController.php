<?php

namespace SofaChamps\Bundle\FeedBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/feed")
 */
class FeedController extends BaseController
{
    /**
     * @Route("/", name="feed_main")
     * @Template
     */
    public function mainAction()
    {
        $user = $this->getUser();
        $feed = $this->getUserFeed($user);

        return array(
            'feed' => $feed,
        );
    }
}
