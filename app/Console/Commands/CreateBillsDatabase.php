<?php

namespace App\Console\Commands;

use App\Helpers\Facade\Switcher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateBillsDatabase extends Command
{
    protected $msg;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:database {--dbname=} {--connection=?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to create bills migrations on tenants database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->msg = ["code" => 200 , "msg" => "ok"];
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return int
     */

    public function handle()
    {
         try{
            $dbname = $this->hasOption('dbname') ?? $this->option('dbname') ? $this->option('dbname') : $this->option('dbname') ;
            $connection = $this->hasOption('connection') && $this->option('connection') ? $this->option('connection'):  $this->option('connection');
            //info($this->option('connection'));
            //DB::connection()->getPDO()->getAttribute(PDO::ATTR_DRIVER_NAME)

             App::make('config')->set('database.connections.teams.database', $dbname);
             DB::purge($connection);
             DB::reconnect($connection);
             Schema::connection($connection)->getConnection()->reconnect();

            if(!Switcher::hasDB($dbname)) {
                DB::statement('CREATE DATABASE '. $dbname);
                 $this->msg["msg"] = "Database '$dbname' created for '$connection' connection";
                $this->info("Database '$dbname' created for '$connection' connection");
                Artisan::call('migrate', array('--database' => $connection, '--path' => 'database/migrations/bills/'));
             //   Artisan::call("db:seed", array('--database' => $connection,'--class' => 'Database\Seeders\DatabaseBillsSeeder'));
            }
            else {
                $this->msg["code"] = 502;
                $this->msg["msg"]= "Database $dbname already exists for $connection connection";
                $this->info("Database $dbname already exists for $connection connection");
                //Artisan::call('migrate', array('--database' => $connection,'--path' => 'database/migrations/bills/'));
                Artisan::call("db:seed", array('--database' => $connection,'--class' => 'Database\Seeders\DatabaseBillsSeeder'));
                $this->info(Artisan::output());
                $this->info("make migration _");
            }
        }
        catch (\Exception $e){
            $this->msg["code"] = 500;
            $this->msg["msg"] = $e->getMessage();
            $this->error($e->getMessage());
        }
        //return $this->msg;
    }




}
