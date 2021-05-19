<?php

namespace App\Repos\DbRepo;

class IndexType
{
    const INDEX_TYPE_NO         = 0;
    const INDEX_TYPE_PRIMART    = 1;
    const INDEX_TYPE_KEY        = 2;

    public static function getIndexType($value) {
        $ret = self::INDEX_TYPE_NO;
        switch ($value) {
            case 'PRI':
                $ret = self::INDEX_TYPE_PRIMART;
                break;
            case 'MUL':
                $ret = self::INDEX_TYPE_KEY;
                break;
        }
        return $ret;
    }
}
