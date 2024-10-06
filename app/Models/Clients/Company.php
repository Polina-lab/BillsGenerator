<?php

namespace App\Models\Clients;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ["name", "phone", "reg_num", "comment","address",  "email", "lepping", "status", "extra", "client_id", "user_id" ];

    public function clients(){
        return $this->belongsTo("\App\Models\Clients\Clients" , "client_id");
    }

}
