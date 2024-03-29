<?php


namespace App\Helpers;


class StageHelper
{
    const GROUP_STAGE = 'GROUP_STAGE';
    const ROUND16 = 'ROUND16';
    const QUARTER8 = 'QUARTER8';
    const SEMI4 = 'SEMI4';
    const FINAL2 = 'FINAL2';

    private static $stageAttributes = [
        'GROUP_STAGE' =>  'Group Stage',
        'ROUND16' => 'Round of 16',
        'QUARTER8' => 'Quarter-final',
        'SEMI4' => 'Semi-final',
        'FINAL2' => 'Final',
    ];

    private static $stageRate = [
        'GROUP_STAGE' =>  1,
        'ROUND16' => 3,
        'QUARTER8' => 4,
        'SEMI4' => 7,
        'FINAL2' => 10,
    ];

    public static function getRate($key)
    {
        return self::$stageRate[$key];
    }
    public static function getName($key)
    {
        return self::$stageAttributes[$key];
    }

    public static function getList()
    {
        return [
            self::GROUP_STAGE,
            self::ROUND16,
            self::QUARTER8,
            self::SEMI4,
            self::FINAL2
        ];
    }
}