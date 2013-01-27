<?php

namespace SofaChamps\Bundle\CoreBundle\Util\Math;

class SigmaUtils
{
    public static function summation($base)
    {
        $result = 0;
        for ($x = 0; $x <= abs($base); $x++) {
            $result += $x;
        }

        return $result;
    }
}
