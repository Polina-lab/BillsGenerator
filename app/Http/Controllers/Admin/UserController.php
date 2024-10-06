<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HasDigitIdRequest;
use App\Http\Requests\Users\ChangePassword;
use App\Http\Requests\Users\CreateUser;
use App\Http\Requests\Users\InviteUser;
use App\Http\Requests\Users\UpdateUser;
use App\Http\Resources\ArrayTranducerWData;
use App\Http\Resources\User\getUserResource;
use App\Repositories\Team\TeamRep;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Integer;
use App\Http\Requests\HasDigitIdorNullRequest;

class UserController extends Controller
{

    protected $user;
    protected $msg;
    protected $t;

    public function __construct(UserService $u , TeamRep $t)
    {
        $this->team = $t;
        $this->user = $u;
        $this->msg = [];
    }

    /** GET ALL
     * @return \App\Models\Users\User[]|\Illuminate\Database\Eloquent\Collection
     */

    public function getAll(){
        return $this->user->getAll();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */

    public function getAllWithPermission(){
        return $this->user->getAllWithPermission();
    }

    /** GET USER
     * @param integer | null $id
     * @return getUserResource
     */

    public function getById( Request $id) : getUserResource{
         $user = $this->user->getById($id->id ?? null);
        if(isset($user->email))
            $this->msg = ["code" => 200, "msg" => "loggedIn", 'user' => $user];
        else
            $this->msg = $user;

         return new getUserResource($this->msg);
    }


    /** CREATE USER
     * @param CreateUser $data
     * @return getUserResource
     */

    public function create(CreateUser $data){
        $this->msg = $this->user->create($data->all());
        return new getUserResource($this->msg);
    }

    /** UPDATE USER
     * @param HasDigitIdRequest $id
     * @param UpdateUser $data
     * @return getUserResource
     */

    public function update(HasDigitIdRequest $id , UpdateUser $data){
        $this->msg = $this->user->update($id->id ,  $data->all());
        return new getUserResource($this->msg);
    }

    /** UPDATE PASSWORD
     * @param HasDigitIdRequest $id
     * @param ChangePassword $data
     * @return getUserResource
     */

    public function update_password(HasDigitIdRequest $id, ChangePassword $data) {
        $this->msg = $this->user->update_password($id->id ,  $data->all());
        return new getUserResource($this->msg);
    }

    /** UPDATE DEFAULT TEAM
     * ['id']
     * @param Request $request
     * @return ArrayTranducerWData
     */

    public function setDefaultTeam(Request  $request){
        $this->msg = ['code' => 200, 'msg' => "Default Team Updated", 'type' => 'success'];
        if(!auth()->user()->update(['default_team' => $request->id]))
            $this->msg = ['code' => 404 , 'msg' => "Oops : team not found" , 'type' => 'error'];
        return new ArrayTranducerWData($this->msg);
    }

    /** UPDATE NOTIFICATION
     * ['status']
     * @param Request $request
     * @return ArrayTranducerWData
     */

    public function disable_notifications(Request $request){
        $this->msg = ['code' => 200  , 'msg' => 'Notifications was disabled' , 'type' => 'success'];
        if(!auth()->user()->update(['disable_suggestions' =>  ($request->status == true )  ? 0  : 1]))
            $this->msg = ['code' => 500  , 'msg' => 'Oops : notification not updated' , 'type' => 'error'];
        return new ArrayTranducerWData( $this->msg);
    }

    /** DELETE USER
     * @param HasDigitIdRequest $id
     * @return ArrayTranducerWData
     */

    public function delete(HasDigitIdRequest $id){
        return new ArrayTranducerWData($this->user->delete($id->id));
    }

    /** INVITE USER
     * @param InviteUser $data
     * @return ArrayTranducerWData
     */

    public function invite(InviteUser $data){
        $this->msg = $this->user->invite($data->all());
        return new ArrayTranducerWData($this->msg);
    }


}
