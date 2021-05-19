<?php

namespace App\Repos\DbRepo;

use Faker\Factory;

class DataHandler
{
    const HANDLERS = [
        DataType::DATA_TYPE_UINT    => 'fakeUint',
        DataType::DATA_TYPE_INT     => 'fakeInt',
        DataType::DATA_TYPE_CHAR    => 'fakeChar',
        DataType::DATA_TYPE_VARCHAR => 'fakeVarchar',
    ];

    public static function getHandler($type) {
        return self::HANDLERS[$type] ? [self::class, self::HANDLERS[$type]] : null;
    }

    private static $faker;
    private static function getFaker() {
        if (!self::$faker) {
            self::$faker = Factory::create();
        }
        return self::$faker;
    }

    public static function fakeUint($subType, $length) {
        $faker = self::getFaker();
        return $faker->$subType(intval($length));
    }

    public static function fakeInt($subType, $length) {
        $faker = self::getFaker();
        return $faker->$subType(intval($length));
    }

    public static function fakeChar($subType, $length) {
        return '';
    }

    public static function fakeVarchar($subType, $length) {
        $faker = self::getFaker();
        return $faker->$subType(intval($length));
    }
}
