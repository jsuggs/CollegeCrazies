<?php

namespace CollegeCrazies\Bundle\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController
{
    protected function renderLogin(array $data)
    {
        return $this->container->get('templating')->renderResponse('CollegeCraziesUserBundle:Security:login.html.twig', $data);
    }
}
