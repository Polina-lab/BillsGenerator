<?php

namespace App\Models\Bills;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $table ="roles";
    public  $timestamps = false;

    public $fillable = ["id" ,"name" , "sysname", "is_enabled"];

    public function user(){
        return $this->hasMany('App\Models\Bills\Users', 'roles_id');
    }

    public function permissions()
    {
        return $this->belongsToMany('App\Models\Bills\Permission' , "pivot_role_permissions" , "roles_id" , 'permissions_id');
    }

    /**
     * Determine if the role has the given permission.
     *
     * @param  mixed $permission
     * @return boolean
     */
    public function inRole($permission)
    {
        if (is_string($permission)) {
            return $this->permissions->contains('name', $permission);
        }
        return !! $permission->intersect($this->permissions)->count();
    }

}
