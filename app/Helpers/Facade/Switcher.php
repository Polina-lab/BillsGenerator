<?php
namespace App\Helpers\Facade;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Switcher
{

    /** This method set using local database by Key
     * @param string $database
     */
    public static function useLocalDB( string $database ){
        DB::disconnect('mysql');
        Config::set('database.connections.mysql.database', $database);
        //Config::set('database.connections.teams.database', $database);
        DB::purge('mysql');
    }

    /**
     * This method set default database bills
     */

    public static function useDefaultDB(){
        DB::disconnect('mysql');
        Config::set('database.connections.mysql.database', "bills");
        //Config::set('database.connections.teams.database', "bills");
        DB::purge('mysql');
    }

    /** Method to check table by name
     * @param $name
     * @return bool
     */

    public static function checkDB($name){
        return Schema::hasTable($name);
    }

    /** to check database by name
     * @param $dbname
     * @return bool
     */

    public static function hasDB($dbname){
        $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
        $hasDb = DB::select($query, [$dbname]);
        return !empty($hasDb);
    }

}
