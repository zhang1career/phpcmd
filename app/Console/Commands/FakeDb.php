<?php

namespace App\Console\Commands;

use App\Repos\DbRepo\DbConfig;
use App\Services\DbService;
use App\Utils\ui\CliUIUtil;
use Illuminate\Console\Command;

class FakeDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fakedb {tables} {num}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert fake data into db';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tables = explode(',', $this->argument('tables'));
        if (!$tables) {
            $this->info("Arguments error, tables needed.");
            return 0;
        }

        $num = $this->argument('num');
        if (!is_numeric($num) || $num <=  0) {
            $this->info("Arguments error, num should be positive integer.");
            return 0;
        }

        foreach ($tables as $table) {
            for ($i = 0; $i < $num; $i += DbConfig::DB_WRITE_SIZE_MAX) {
                DbService::fakeData($table, min($num - $i, DbConfig::DB_WRITE_SIZE_MAX));
                // show process bar
                CliUIUtil::showProgressBar(intval($i * 100 / $num));
            }
        }

        return 1;
    }


}
