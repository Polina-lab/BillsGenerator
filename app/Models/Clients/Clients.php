<?php

namespace App\Models\Clients;

use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    public $table = "clients";

    protected $fillable = [
        "firstname", "lastname",
        "address", "id_code",
        "comment", "lepping",
        "work_status", "agent",
        'status', "phone",
        "reg_num", "email",
        "show_in_bills"
    ];

    protected  $appends = ['username' => "username"];

    public function getUsernameAttribute(){
        return isset($this->firstname) ? $this->firstname .' '. $this->lastname : "" ;
    }

    public function billss(){
        return $this->belongsToMany("App\Models\Bills\Bills" ,  "pivot_client_bill" , "clients_id" , "bills_id" );
    }

    public function bills(){
        return $this->hasMany("App\Models\Bills\Bills" ,"client_id" );
    }


    public function companies(){
        return $this->hasMany("\App\Models\Clients\Company" , "client_id");
    }

}
