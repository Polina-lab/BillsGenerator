<?php


namespace App\Repositories\User;


use App\Models\Users\User;
use App\Models\Bills\Users as UserBill;
use App\Notifications\MailSender;
use App\Repositories\Team\TeamRep;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class UserRep
{
    public $user;
    public $user_bill;
    protected $team;

    public function __construct(User $u , UserBill $ub , TeamRep $t)
    {
        $this->user = $u;
        $this->user_bill = $ub;
        $this->team = $t;
    }

    public function getById(int $id){
        return $this->user->where("id" , $id)->first();
    }


    public function getByEmail(string  $email){
        return $this->user->where("email" , $email)->first();
    }

    public function getByEmailWithPassword(string  $email){
        return $this->user->with("password")->where("email" , $email)->first();
    }
/*
    public function getByEmailFull(string  $email){
        return $this->user->with("password" , "team")->where("email" , $email)->first();
    }*/

    public function getNotVerified(){
        return $this->user->where("email_verified_at" , null)->get();
    }


    public function refreshToken(int $token_id){
       return  DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $token_id)
            ->update([
                'revoked' => true
            ]);
    }

    /** get all users
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     */

    public function getAll(){
        return $this->user->all();
    }

    /** return all buyers
     * @return mixed
     */

    public function getBuyers(){
        return $this->user->with('team')->where('has_buyer' , 1)->get();
    }

    /** get all users with permission
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */

    public function getAllWithPermission(){
        return $this->user->with(["roles" , "permissions" ])->get();
    }

    /** Restore password
     * @param User $user
     * @param array $data
     * @return User
     */

    public function restore(User $user, array $data){
        $user->password()->update(["password" => bcrypt($data["password"]) ]);
        return $user;
    }

    /**
     * @param array $data
     * @return mixed
     */

    public function     register(array $data){
        $user = $this->user->create(array_merge(['has_buyer' => true  ] , $data));
        $data["theme"] = "register";
        Notification::route('mail', $user->email)->notify(new MailSender($data , $user->email));
        $user->password()->create(["password" => bcrypt($data["password"])]);
        $t = $this->team->create_new_team();
        $user->team()->sync($t->id);
        return $user;
    }


    /** to create local user
     * @param array $data
     * @return mixed
     */

    public function createLocalUser(array $data){
        return  $this->user_bill->create($data);
    }

    /** to set TempPassword
     * @param User $user
     * @param array $data['password'] need be bcrypt !
     */

    protected function setTempPassword(User $user , array $data) {
        if($data["password"]){
            $user->password()->create(["password" => $data["password"]]);
        }
    }


    public function createGlobalUserWithoutEvents($data){
        $user =  $this->user->withoutEvents(function () use ($data) {
            return $this->createGlobalUser($data);
        });

        $this->setTempPassword($user , $data);

        return $user;
    }

    /**  create global user
     * @param $data
     * @return mixed
     */

    public function createGlobalUser($data){
        $user =  $this->user->create($data);
        if($data["team_id"]){
            $user->team()->attach([$data["team_id"]]);
        }
        return $user;
    }

    /** function to create or update Local User
     * @param array $data
     * @return array
     * 400 - Cannot create an user using this email
     * 200 - User Created
     * 500 - User not created
     */

    public function create_or_update_local(array $data){
        $has = $this->user_bill->where("email" , $data['email'])->first();
        if($has){
            $has->update($data);
            return [
                'msg' => "User updated",
                'type' => "success",
                'user' => $has,
                'code' => 200,
            ];
        }else
            if(!isset($data["roles_id"])){
                $data["roles_id"] = 1;
            }

            $local_user = $this->createLocalUser($data);
            if($local_user)
                return [
                'msg' => "User Created",
                'type' => "success",
                'user' => $local_user,
                'code' => 200];
            else
                return [
                  "msg" => "User not Created",
                  'type' => "error",
                  "code" => 500
                ];
    }

    /** method for update global user
     * @param int $id
     * @param array $data
     */

    public function update(int $id , array  $data){
        return $this->getById($id)->update($data);
    }

    /** method to delete global method
     * @param int $id
     * @return mixed
     */
    public function delete(int $id){
        return $this->getById($id)->delete($id);
    }

}
