<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\User\PermissionRep;

class PermissionController extends Controller
{
    protected $permission ;

    public function __construct(PermissionRep $p){
        $this->permission = $p;
    }

    public function getAll(){
        return $this->permission->getAll();
    }

}
