<?php

namespace App\Console\Commands;

use App\Helpers\Facade\Switcher;
use App\Repositories\Team\TeamRep;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class fixDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'replace:column';


    protected $team;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete this command after running';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TeamRep $t)
    {
        $this->team = $t;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $teams = $this->team->getAll();
        $connection = 'mysql';
        foreach ($teams as $team){
            if($team->has_created) {
                App::make('config')->set('database.connections.mysql.database', $team->database);
                DB::purge($connection);
                DB::reconnect($connection);
                \Illuminate\Support\Facades\Schema::connection($connection)->getConnection()->reconnect();
                try {
                    $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
                    $hasDb = DB::select($query, [$team->database]);
                    $this->info($team->database);
                    if (!empty($hasDb)) {
                        $this->info($team->database);
                        Switcher::useLocalDB($team->database);
                        if (Schema::hasColumns('pivot_orders_bills', ['bill_id', 'order_id'])) {
                            DB::select("ALTER TABLE `pivot_orders_bills` CHANGE `bill_id` `orders_id` BIGINT UNSIGNED NOT NULL");
                            DB::select("ALTER TABLE `pivot_orders_bills` CHANGE `order_id` `bills_id`  BIGINT UNSIGNED NOT NULL");
                        }
                    }
                }catch(\Exception $e){
                    $this->info($e->getMessage());
                }
            }
        }


        return 0;
    }
}
