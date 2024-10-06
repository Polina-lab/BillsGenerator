<?php
namespace App\Services\Bills;

use App\Repositories\Firm\FirmRep;
use App\Repositories\Team\TeamRep;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

class FirmService
{
    protected  $firm;
    protected  $msg;
    protected $team;

    public function __construct(FirmRep $f , TeamRep $t){
        $this->firm = $f;
        $this->team = $t;
        $this->msg = ["code" => 200 , "msg" => "Ok" , 'type' => "success"];
    }

    public function getAll(){
        return $this->firm->getAll();
    }

    public function create(array $data){
            if(auth()->user()->team === null){
            //TODO: add create team
            $nameDB = "bills_test_" . rand(1 , 99);
            DB::statement('CREATE DATABASE ' . $nameDB);
            App::make('config')->set('database.connections.teams.database', $nameDB);
            Artisan::call('migrate', array('--database' => "teams", '--path' => 'database/bills'));
        }


        if($this->firm->findByName($data["name"])->count() > 0){
                $this->msg = ["code" => 500 , "msg" => "Firm with name ". $data["name"] ." was created " , "type" =>"error"] ;
            }else {
                    $res = $this->firm->create($data);
                    if($res)
                        $this->msg = ["code" => 200 , "msg" => "Firm Created" , "type" =>"success" , "firm" => $res->toArray() ] ;
                    else
                        $this->msg = ["code" => 500 , "msg" => "Oops : firm not created"  , "type" =>"error"] ;
                }
        return $this->msg;
    }

    public function delete(int $id){
        if($this->firm->delete($id))
            $this->msg = ["code" => 200 , "msg" => "Firm updated" , "type"=> "success"];
        else
            $this->msg = ["code" => 500 , "msg" => "Oops : firm not created"  , "type" =>"error"] ;

        return $this->msg;
    }

    public function update(int $id , array $data){
        if($this->firm->update($id , $data))
            $this->msg = ["code" => 200 , "msg" => "Firm updated" , "type"=> "success"];
        else
            $this->msg = ["code" => 500 , "msg" => "Oops : firm not created"  , "type" =>"error"] ;

        return $this->msg;
    }





}
