<?php

namespace App\Repositories\Team;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\Users\Team;
use Illuminate\Support\Str;

class TeamRep
{

    protected $team ;

    public function __construct(Team $t){
        $this->team =  $t;
    }

    /** get all Teams
     * @return Team[]|\Illuminate\Database\Eloquent\Collection
     */

    public function getAll(){
        return $this->team->with(['user' , 'payment' , 'langs'])->get();
    }


    /** get By Id
     * @param $key
     * @return mixed
     */

    public function getByKey($key){
        return $this->team->where("key" , $key)->first();
    }

    /**
     * @param $team
     * @return $this
     */

    public function switchDB($team){
        if($team) {
            if(!empty($team->timezone)) {
                Config::set('app.timezone', $team->timezone);
            }
            DB::disconnect('mysql');
            Config::set('database.connections.mysql.database', $team->database);
            DB::purge('mysql');
        } return $this;
    }

    /**
     * @return mixed
     */

    public function create_new_team(){
       return $this->team->create([
           "database" => (string) "A_". uniqid()."_db" ,
           "name" =>  Str::random(7) . '_team',
           "active_until" => Carbon::now()->addDays(3),
           "payment_plan_id" => 0 , // by default
           'current_balance' => 0, // by default
           "key" => Str::uuid()]);
    }


    public function update(){



    }




    public function delete(){


    }

}
