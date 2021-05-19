<?php

namespace App\Services;

use App\Repos\DbRepo\DataHandler;
use App\Repos\DbRepo\DataType;
use App\Repos\DbRepo\DbConfig;
use App\Repos\DbRepo\IndexType;
use Illuminate\Support\Facades\DB;

class DbService
{
    public static function fakeData($table, $num) {
        $metas = self::getMetas($table);
        $datas = self::genDatas($metas, min($num, DbConfig::DB_WRITE_SIZE_MAX));
        self::insert($table, $datas);
    }

    public static function getMetas($table) {
        $columns = self::getColumns($table);
        $ret = [];
        foreach ($columns as $column) {
            list($type, $method, $length) = DataType::getDataType($column);
            $ret[] = [
                'name' => $column->Field,
                'type' => $type,
                'method' => $method,
                'length' => $length,
                'key' => IndexType::getIndexType($column->Key),
            ];
        }
        return $ret;
    }

    public static function genDatas($metas, $num) {
        $datas = [];
        for ($i = 0; $i < $num; $i++) {
            $data = [];
            foreach ($metas as $meta) {
                if ($meta['key'] == IndexType::INDEX_TYPE_PRIMART) {
                    continue;
                }
                $name = $meta['name'];
                $handler = DataHandler::getHandler($meta['type']);
                $data[$name] = call_user_func($handler, $meta['method'], $meta['length']);
            }
            if ($data) {
                $datas[] = $data;
            }
        }
        return $datas;
    }

    private static function getColumns($table) {
        return DB::select(DB::raw("SHOW COLUMNS FROM $table"));
    }

    private static function insert($table, $data) {
        DB::table($table)->insert($data);
    }
}
