<?php


namespace App\Helpers;


class CommonHelper
{

    public static function float2rat($n, $tolerance = 1.e-6)
    {
        if (is_int($n) || intval($n) == $n) {
            return $n;
        }
        $h1 = 1;
        $h2 = 0;
        $k1 = 0;
        $k2 = 1;
        $b = 1 / $n;
        do {
            $b = 1 / $b;
            $a = floor($b);
            $aux = $h1;
            $h1 = $a * $h1 + $h2;
            $h2 = $aux;
            $aux = $k1;
            $k1 = $a * $k1 + $k2;
            $k2 = $aux;
            $b = $b - $a;
        } while (abs($n - $h1 / $k1) > $n * $tolerance);
        return "$h1/$k1";
    }
}