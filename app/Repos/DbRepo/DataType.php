<?php

namespace App\Repos\DbRepo;

class DataType
{
    const DATA_TYPE_UNKNOWN = 0;
    const DATA_TYPE_UINT    = 1;
    const DATA_TYPE_INT     = 2;
    const DATA_TYPE_CHAR    = 11;
    const DATA_TYPE_VARCHAR = 12;


    public static function getDataType($column) {
        $type = DataType::DATA_TYPE_UNKNOWN;
        $length = 0;
        preg_match('/(\w+)\(?(\d*)\)?\s*(\w*)/', $column->Type, $matches);
        switch ($matches[1]) {
            case 'int':
                if ($matches[3]) {
                    $type = DataType::DATA_TYPE_UINT;
                    $length = 11;
                } else {
                    $type = DataType::DATA_TYPE_INT;
                    $length = 11;
                }
                break;
            case 'varchar':
                $type = DataType::DATA_TYPE_VARCHAR;
                $length = $matches[2];
        }

        list($method, $subLength) = self::getMethod($type, $column->Field);

        return array($type, $method, min($length, $subLength));
    }


    /**
     * @var \string[][]
     *     格式：'name' => 'method:length'
     */
    private static $DATA_SUBTYPE_MAP = [
        self::DATA_TYPE_VARCHAR => [
            'phone' => 'randomNumber:8',
        ]
    ];

    public static function getMethod($type, $name)
    {
        if (isset(self::$DATA_SUBTYPE_MAP[$type][$name])) {
            return explode(':', self::$DATA_SUBTYPE_MAP[$type][$name]);
        }

        // 'xxxx_id'
        preg_match('/^\w*_(id)$/', $name, $matches);
        if ($matches) {
            return ['randomNumber', 5];
        }

        // 'xxxx_time'
        preg_match('/^\w*_(time)$/', $name, $matches);
        if ($matches) {
            return ['randomNumber', 8];
        }

        return [$name, 100];
    }
}
