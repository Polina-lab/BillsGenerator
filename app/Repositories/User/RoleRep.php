<?php


namespace App\Repositories\User;

use App\Models\Users\Roles;
class RoleRep
{
    public $role;
    protected $msg;

    public function __construct( Roles $r){
        $this->role = $r;
        $this->msg = [];
    }

    public function getById(int $id){
        return $this->role->where("id" , $id)->first();
    }


    public function getByName(string $name){
        return $this->role->where("name" , $name)->first();
    }

    public function getAll(){
        return $this->role->all();
    }

    public function getAllWithPermissions(){
        return $this->role->with(["permissions", "users"])->get();
    }

    public function getPermissions($role_id){
        $role = $this->getById($role_id);
        return $role->permissions;
    }

    public function  update(array $data){
        $role = $this->getById($data["id"]);
        if($role) {
            $role->update($data);
            $this->msg = ["msg" => "Role updated" , "status" => "success" , "code" => 200];
        }else{
            $this->msg = ["msg" => "Role not found" , "status" => "error" , "code" => 500];
        }
        return $this->msg;
    }

    public function create(array $data){
        if($this->getByName($data["name"]) != null){
            $this->msg = ["msg" =>"Oops : name not uniq" ,"status" => "error" ,  "code" => 404];
        }else
            try{
                $this->role->create($data);
                $this->msg = ["msg" => "Role created" ,"status" => "success" ,  "code" => 200];
            }catch(\Exception $e){
                $this->msg = ["msg" => $e->getMessage() , "status" => "error" , "code" => 500];
            }
        return $this->msg;
    }

    /** Method to delete role
     *  status :
     *  404 - not Found
     *  206 - has attached permissions
     *  200 - ok
     *  500 - Exception
     *
     * @param int $id
     * @return array
     */


    public function delete(int $id){
        $role = $this->getById($id);
        if($role === null){
            $this->msg  = ["msg" => "Oops : this role not found" , "status" => "error" , "code" => 404];
        }else
            try {
                if($role->permissions()->count() > 0){
                    $this->msg = ["msg" => "Oops: firstly detach permissions!!" , "code" => 206 , "status" => "error"];
                }else {
                    $role->delete();
                    $this->msg = ["msg" => "Role deleted" , "code" => 200 , "status" => "success"];
                }
            }catch (\Exception $exception){
                $this->msg = ["msg" => "Oops : " . $exception->getMessage() , "status" => "error" , "code" => 500];
            }
        return $this->msg;
    }

}
