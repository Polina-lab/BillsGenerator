<?php

namespace App\Models\Bills;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = "permissions";
    protected $fillable = ['name' , 'label' , 'description'];


    public function roles(){
        return $this->belongsToMany('App\Models\Users\Bills\Role' , "permission_role_pivot");

    }



}
