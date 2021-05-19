<?php


namespace App\Utils\ui;


class CliUIUtil
{
    /**
     * 进度条
     * @param int $percent
     */
    public static function showProgressBar($percent = 0)
    {
        echo "\033[?25l";//隐藏光标
        $process = "";
        for ($i = 1; $i <= $percent; $i++) {
            $process .= "|";
        }
        echo "\033[32m" . $process . "\033[33m$percent%";
        echo "\033[105D";//移动光标到行首，105是进度条最大长度，再大点没关系

        if (100 == $percent) {
            echo "\n\33[?25h";
        }
    }
}
