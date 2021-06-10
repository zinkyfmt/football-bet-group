<?php


namespace App\Helpers;


class ResultStatusHelper
{
    const WIN = 'win';
    const DRAW = 'draw';
    const LOSE = 'lose';

    private static $stageAttributes = [
        'win' =>  'Win',
        'draw' => 'Draw',
        'lose' => 'Lose',
        'SEMI4' => 'Semi-final',
        'FINAL2' => 'Final',
    ];

    public static function getName($key)
    {
        return self::$stageAttributes[$key];
    }
}