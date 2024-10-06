<?php

namespace App\Repositories\User;

use App\Models\Bills\Users as UserBill;
use App\Models\Users\User;
use App\Notifications\MailSender;
use Illuminate\Support\Facades\Notification;
use App\Repositories\Team\TeamRep;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserLocalRep
{
    public $user;
    protected $team;

    public function __construct(  UserBill $u , TeamRep $t )
    {
        $this->user = $u;
        $this->team = $t;
    }

    /**
     * @param int $id
     * @return mixed
     *
     */

    public function getById(int $id )
    {
        return $this->user->with('roles' , 'langs', 'roles.permissions')->where("id", $id)->first();
    }

    /** method to check password in local data
     * @param  $user
     * @param string $password
     * @return bool
     */

    public function check_password($user , string $password ) :bool{
        return (isset($user) ?  !Hash::check(trim($password), $user->password) : false);
    }

    public function getLocalData()
    {
        return $this->user->localData()->first();
    }

    public function getByEmail(string $email)
    {
        return $this->user->where("email", $email)->first();
    }

    public function getByEmailWithPassword(string $email)
    {
        return $this->user->with("password")->where("email", $email)->first();
    }

    public function getByEmailFull(string $email)
    {
        return $this->user->with("password", "team")->where("email", $email)->first();
    }

    public function reset_password($user): array{
        $new_password = Str::random(6);
        if($user->update(['password' => bcrypt($new_password)])){
            $body= ['theme' => 'restore' , 'password' => $new_password];
            Notification::route('mail' , $user->email)->notify(new MailSender( $body , $user->email));
            $msg = ['msg' =>  'Password was sent on mail' , 'code' => 200 ];
        }else {
            $msg = ['msg' =>  'Oops : Password not updated' , 'code' => 500 ];
        }
        return $msg;
    }


    public function getNotVerified()
    {
        return $this->user->where("email_verified_at", null)->get();
    }

    public function refreshToken(int $token_id)
    {
        return DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $token_id)
            ->update([
                'revoked' => true
            ]);
    }


    /** get all users
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     */

    public function getAll()
    {
        return $this->user->all();
    }

    /** get all users with permission
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */

    public function getAllWithPermission()
    {
        return $this->user->with(["roles", "permissions"])->get();
    }

    /** Restore password
     * @param User $user
     * @param array $data
     * @return User
     */

    public function restore( array $data)
    {
        return  $this->user->password()->update(["password" => bcrypt($data["password"])]);
    }

    /**
     * @param array $data
     * @return mixed
     */

    public function create(array $data)
    {
        return $this->user->create($data);
    }

    /** method to update local user
     * @param int $id
     * @param array $data
     * @return mixed
     */

    public function update(int $id, array $data)
    {
        return $this->getById($id)->update($data);
    }

    public function delete(int $id)
    {
        return $this->getById($id)->delete();
    }

}
