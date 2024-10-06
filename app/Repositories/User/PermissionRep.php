<?php

namespace App\Repositories\User;


use App\Models\Users\Permissions;

class PermissionRep
{
    protected $permission;

    public function __construct(Permissions $p){
        $this->permission = $p;
    }

    public function getAll(){
        return $this->permission->all();
    }


}
