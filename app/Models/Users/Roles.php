<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users\Permissions;

class Roles extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ["name" , "sys_name" , "is_enabled"];

    public function users(){
        return $this->hasMany(User::class , 'roles_id' );
    }

    public function permissions(){
        return $this->belongsToMany(Permissions::class , "pivot_role_permissions");
    }


}
