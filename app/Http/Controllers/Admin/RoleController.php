<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HasDigitIdRequest;
use App\Http\Requests\Permission\UpdateTableRequest;
use App\Http\Requests\Role\CreateRequest;
use App\Http\Requests\Role\UpdateRequest;
use App\Http\Resources\ArrayTranducerWData;
use App\Http\Resources\ArrayTransducer;
use App\Repositories\User\RoleRep;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $role;

    public function __construct(RoleRep $r){
        $this->role = $r;
    }

    /** Method to get All Roles
     * @return \App\Models\Users\Roles[]|\Illuminate\Database\Eloquent\Collection
     */

    public function getAll(){
        return $this->role->getAll();
    }

    /** Method to get all roles with permissions
     * @return \App\Models\Users\Roles[]|\Illuminate\Database\Eloquent\Collection
     */

    public function getAllWithPermissions(){
        return $this->role->getAllWithPermissions();
    }

    /** Method to get permission by role
     * @param $role_id
     * @return mixed
     */

    public function getPermissions($role_id){
        return $this->role->getPermissions($role_id);
    }

    /** Method to create new role
     * @param CreateRequest $data
     * @return ArrayTransducer
     */

    public function create(CreateRequest  $data){
        return new ArrayTransducer($this->role->create($data->all()));
    }

    /** PATCH  Method to update role
     * @param Request $data
     * @return ArrayTransducer
     */

    public function update(UpdateRequest  $data){
        return new ArrayTransducer($this->role->update($data->all()));
    }

    /** Method to get table
     * @return array
     */
    public function getTable(){
        $table = [];
        foreach($this->role->getAll() as $role){
            $table[$role->id]['permissions'] = $role->permissions ?? null;
            $table[$role->id]['users'] =  $role->user ?? [];
        }
        return $table;
    }

    /** Method to delete role
     * @param $id
     * @return ArrayTransducer
     */

    public function delete(HasDigitIdRequest $id){
        return new ArrayTransducer($this->role->delete($id->id));
    }

    /** Route to update table with permissions
     * @param UpdateTableRequest $request
     * @return ArrayTranducerWData
     */

    public function update_table( UpdateTableRequest $request){
        $role = $this->role->getById($request->get('role'));
        if($role){
            $permission_id = [];

            if($request->has("permissions"))
                foreach ( $request->get("permissions") as $p){
                    $permission_id[] = $p["id"];
                }
            $role->permissions()->sync($permission_id);
            $this->msg = ['code' => 200 , 'msg' => 'updated' , "type" => "success"];
        }else{
            $this->msg = ['code' => 500 , 'msg' => "Role or Permissions Not Found" , "type" => "error"];
        }
        return new ArrayTranducerWData($this->msg);
    }


}
