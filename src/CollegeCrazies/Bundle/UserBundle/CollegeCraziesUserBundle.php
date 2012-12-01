<?php

namespace CollegeCrazies\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CollegeCraziesUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
