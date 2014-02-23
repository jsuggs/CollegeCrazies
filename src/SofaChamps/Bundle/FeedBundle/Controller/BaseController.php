<?php

namespace SofaChamps\Bundle\FeedBundle\Controller;

use SofaChamps\Bundle\CoreBundle\Controller\CoreController;
use SofaChamps\Bundle\CoreBundle\Entity\User;

class BaseController extends CoreController
{
    public function getUserFeed(User $user)
    {
        return $this->getRepository('SofaChampsFeedBundle:FeedItem')->findAll();
    }
}
