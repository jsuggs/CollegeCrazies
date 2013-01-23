<?php

namespace SofaChamps\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SofaChampsUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
