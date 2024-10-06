<?php

namespace App\Console\Commands;

use App\Repositories\Team\TeamRep;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class migrateAllDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:allDatabase';

    public  $team ;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for migrate all database';

    /**
     * Create a new command instance.
     * Trans
     * @return void
     */
    public function __construct(TeamRep  $t)
    {
        $this->team  = $t;
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
        $connection = 'teams';
        foreach ($teams as $team){
            if($team->has_created) {
                App::make('config')->set('database.connections.teams.database', $team->database);
                DB::purge($connection);
                DB::reconnect($connection);
                Schema::connection($connection)->getConnection()->reconnect();
                $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
                $hasDb = DB::select($query, [$team->database]);
                $this->info($team->database);
                if (!empty($hasDb)) {
                    Artisan::call('migrate', array('--database' => $connection, '--path' => 'database/migrations/bills/'));
                    $this->info(Artisan::output());
                }
            }
        }
        return 0;
    }
}
