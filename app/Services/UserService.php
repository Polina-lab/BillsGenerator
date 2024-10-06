<?php

namespace App\Services;

use App\Events\CreateNewDatabase;
use App\Helpers\Facade\Switcher;
use App\Models\Bills\BankDetails;
use App\Models\Bills\Firms;
use App\Models\Bills\FirmsVat;
use App\Models\Clients\Clients;
use App\Models\Users\User;
use App\Notifications\MailSender;
use App\Repositories\Firm\FirmRep;
use App\Repositories\Team\TeamRep;
use App\Repositories\User\UserRep;
use App\Repositories\User\UserLocalRep;
use App\Services\File\ImageService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Str;
use App\Models\Bills\Bills;

class UserService
{
    public $user;
    public $user_local;
    public $msg = [];
    protected $team ;
    protected  $firm;


    public function __construct(UserRep $u , UserLocalRep $ul ,TeamRep $t , FirmRep  $f , ImageService  $i)
    {
        $this->user_local = $ul;
        $this->user = $u;
        $this->team = $t;
        $this->firm = $f;
        $this->image= $i;
    }

    /**
     * @return array
     */

    protected function getExtraInfo(){
        return \Illuminate\Support\Facades\Cache::remember('user_extra_info_' .  auth()->user()->id  , 10 , function(){
            return [
                "users_count" => User::all()->count(),
                "bills_count" => Bills::all()->count(),
                'clients_count' => Clients::all()->count(),
                'firms_count' => Firms::all()->count(),
            ];
        });
    }



    /** method to get by id
     * @param int|null $id
     * @return array | User | Exception
     */

    public function getById($id) {
        $user = null;
        if($id!= null)  {
            if (!Switcher::checkDB('teams')) { //
                $user_local = $this->user_local->getById($id);
                if($user_local) {
                    Switcher::useDefaultDB();
                    $user = $this->user->getByEmail($user_local->email);
                    if(!$user){
                        return  ['code' => 404, 'msg' => "Local user not found"];
                    }
                    if($user->team() === null){
                        return ['code' => 404 , 'mag' => "User team not found "];
                    }
                    $user->available_teams = $user->team()->get(["key", "name", "active_until", 'payment_plan_id']);
                    $user->local_data = $user_local;
                }else return ['code' => 404 , 'msg' => 'User not found'];
            }
        }
        else {
            $user = auth()->user();

            if($user) {
                if (Switcher::checkDB('teams')) {
                    $user->available_teams = $user->team()->get();;
                    if ($user->team->count()) {
                        Switcher::useLocalDB($user->team->first()->database);
                        $local_user = $this->user_local->getByEmail($user->email);
                        $user->local_data = $local_user;
                        $user->extra_info  =  $this->getExtraInfo();

                    }
                } else {
                    $local_user = $this->user_local->getById($user->local_id);
                    $user->local_data = $local_user;

                }
            }else return ['code' => 404 , 'msg' => 'User not found'];
        }
        return $user;
    }

    /** method to get by email
     * @param string $email
     * @return mixed
     */

    public function getByEmail( string $email){
        return $this->user->getByEmail($email);
    }

    /** Method to verif account
     * @param string $data
     * @return array
     */

    public function verif(string $data) :array{
        $users =  $this->user->getNotVerified();
        $c_user = $users->where("remember_token" , $data)->first();
        if($c_user){
            $c_user->update(["email_verified_at" => Carbon::now() ]);
            $this->msg = ["msg" => "user.email_was_verified" , "code" => 200 , "data" => [
            "username" => $c_user->username
            ],
            "type" => "success"];
        }else {
            $this->msg = ["msg" => "user.this_token_not_found" , "code" => 404  , "type" => "error"];
        }
        return $this->msg;
    }

    /**
     * @param array $data
     * @return string[]
     */

    public function reset_password(array $data): array
    {
        $user = $this->user->getByEmail($data['email']);
        if(!$user){
            $this->msg =   ['msg' => 'User not found' , 'code' => '404'];
        }
        else {
            $team = $user->team->first();
            if (isset($team->database)) {
                Switcher::useLocalDB($team->database);
                $user_local_data = $this->user_local->getByEmail($user->email);
                if ($user_local_data) {
                   $this->msg =   $this->user_local->reset_password($user_local_data);
                } else {
                    $this->msg = ['msg' => 'User not found in local database', 'code' => '500'];
                }
            } else {
                $this->msg =  ['msg' => 'Local database not found' , 'code' => '500'];
            }
        }
        return $this->msg;
    }

    /** Method to login
     * 501 - PASSWORD NOT SET
     * 500 - DATABASE NOT CREATED YET
     * 404 - USER NOT FOUND
     * 422 = WRONG EMAIL OR PASSWORD
     * 423 = WRONG EMAIL OR PASSWORD  local data
     * 301 - USER NOT ENABLED | UNAUTHORIZED
     * 200 - OK
     *
     * @param $data
     * @return array
     */

    public function login($data){
        $user = $this->user->getByEmailWithPassword($data->username);
        if (!$user) {
            /* USER NOT FOUND */
            return [
                'msg' => 'User not found',
                'type' => "error",
                'code' => 404];
        }else {
            if($user->is_enabled === false){
             /* USER NOT ENABLED */
                return [
                    'msg' => 'Oops : That user not enabled ( ',
                    'type' => "error",
                    'code' => 301
                ];
            }

            $team =  $user->team->first();
            if( $user->local_id !== null & isset($team->database)) {
                $user->update(['last_login' => Carbon::now() ]);
                /**
                 * авторизация по постоянному паролю
                 */
                Switcher::useLocalDB($team->database);
                $user_local_data = $this->user_local->getByEmail($user->email);
               // $user->localData = $user_local_data ?? [];
                if($user_local_data) {
                    if ($this->user_local->check_password($user_local_data, $data->password)) {
                        /* WRONG EMAIL OR PASSWORD */
                        return [
                            'msg' => 'Wrong email or password (local data)',
                            'type' => "error",
                            'code' => 423
                        ];
                    }
                }else{
                    return [
                        'msg' => 'User not found (local data)',
                        'type' => "error",
                        'code' => 424
                    ];
                }
                Switcher::useDefaultDB();
            }else{
                /**
                 * авторизация по временному паролю
                 */
                if (isset($user->password->password)) {
                    if (!Hash::check(trim($data->password), $user->password->password)) {
                        /* WRONG EMAIL OR PASSWORD */
                        return [
                            'msg' => 'Wrong email or password',
                            'type' => "error",
                            'code' => 422
                        ];
                    }
                }else {
                    //SEND MAIL WITH NEW PASSWORD OR link on  FORM TO SET ..
                    return [
                        'msg' => 'Password not set',
                        'type' => "error",
                        'code' => 501
                    ];
                }
            }
                $this->msg = ["token" => $user->createToken("authToken")->plainTextToken ];
                if($team) {
                    $this->msg = array_merge($this->msg, ["key" => $team->key]);
                    $hasDb = DB::connection("teams")->select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = " . "'" . $team->database . "'");
                    if ($hasDb) {
                        if($team->has_created != true) {
                            $team->update(['has_created' => true]);
                        }
                    }else{
                    return $this->createNewDatabase($team);
                    }
                }else{
                        $team = $this->team->create_new_team();
                        $user->team()->sync($team->id);
                        return $this->createNewDatabase($team);
                    }
            }

        return  $this->msg;
    }

    /**
     * @param $team
     * @return array
     */

    protected function createNewDatabase( $team) : array{
        event(new CreateNewDatabase($team->database )); //creating database
        event(new CreateNewDatabase($team->database )); //seeding database
        return [
            'msg' => 'Database for you not created yet.. Please wait 15 sec. and try again(1)',
            'type' => "error",
            'code' => 206
        ];
    }


    protected function checkData( array $data): bool{
        $res = true;
        if(isset($data["password" ]) & isset($data["password2"])){
            if($data["password"] !== $data["password2"] ){
                $this->msg = ["msg" => "Given passwords are not equal!" , "code" => 500 , "type" => "error"];
                $res = false;
            }
        }else {
            $res = false;
            $this->msg = ["msg" => "Request need contain passwords " , "code" => 500 , "type" => "error"];
        }
        return $res;
    }

    /**
     * @param array $data
     * @return bool
     */

    protected function checkPassword(array $data){
        $res = true;
        if($data["password"] !== $data["password2"] ){
               $res = false;
               $this->msg = ["msg" => "This password need be coincide" , "code" => 500 , "status" => "error"];
           }
        return $res;
    }

    /**
     * @param $user
     * @param $password
     * @return array|void
     */

    protected function checkPasswordOnUniq($user , $password ){
        if (!Hash::check(trim($password), $user->password)) {
            /* WRONG EMAIL OR PASSWORD */
            return [
                'msg' => 'Current password does not match',
                'type' => "error",
                'code' => 422
            ];
        }else return ['code' => 200  ];
    }



    /** check email where register or add new account
     * @param $data
     * @return array
     */
    protected function checkEmail( string  $email){
        $res["result"] = true;
        if($email){
            $email_used  = $this->getByEmail($email);
            if($email_used){
                $res["result"] = false;
                $res['user'] = $email_used;
                $this->msg = ["msg" => "This email address is already being used" , "code" => 500 , "status" => "error"];
            }
        }
        return $res;
    }

    /** register
     *
     * @param array $data
     * @return array
     * 200 - ok
     * 422 - email was been used
     * 500 - server error
     */

    public function register( array  $data){
        $has = $this->checkEmail($data["email"]);
        // Todo: delete that when not needed
        if($data["email"] === "rowlinest90@gmail.com" || "demo@test.ru"){
            $data["password"] = 'user90';
        }else $data["password"] = uniqid();
        if($has["result"]){
            $uniq =uniqid();
            $data["remember_token"] = $uniq;
            $user = $this->user->register($data);
           if($user) $this->msg = ["msg" => "User Created", "code" => 200 , "type" => "success"];
           else $this->msg = ["msg" => __CLASS__ .":" . __METHOD__ , 'code' => 500 , "type" => "error"];
        }else $this->msg = ["msg" => "This email address was been used" , "code" => 422 , "type" => "success"];

        return  $this->msg;
    }

    /** get all user
     * @return \App\Models\Users\User[]|\Illuminate\Database\Eloquent\Collection
     */

    public function getAll(){
        return $this->user->getAll();
    }

    /** method to invite by email
     * @param  array $data
     * @return array
     */

    public function invite($data){
        $has_user =  $this->user->getByEmail($data['email']);
        $current_database =   Config::get('database.connections.mysql.database');
        if($has_user == null){

                    $password = Str::random();
                    $data["password"] = bcrypt($password);
                    $data["is_enabled"] = true;
                    $data['email_verified_at'] = null;
                    $data['status'] = 0; //mail was sent
                    $user =  $this->user->createLocalUser($data);
                    $data['local_id'] = $user->id;
                    Switcher::useDefaultDB();
                    $data['team_id'] = auth()->user()->team->where('database' , $current_database)->first()->id;
                    $global_user = $this->user->getByEmail($data['email']);
                    if($global_user == null) {
                        $data['has_buyer'] = 0;
                        $this->user->createGlobalUserWithoutEvents($data);
                        $data["theme"] = "invite";
                        $data['password'] = $password;
                        Notification::route('mail', $data)->notify(new MailSender($data, $data["email"]));
                    }else {
                        $global_user->update(['has_multi' => true]);
                        $global_user->team()->attach([$data['team_id']]);
                        $global_user_first_database =  $global_user->team()->first()->database;
                        Switcher::useLocalDB( $global_user_first_database);
                           $global_user_data_first_database = $this->user_local->getByEmail( $data['email']);
                        Switcher::useLocalDB($current_database);
                            $user->update($global_user_data_first_database->toArray());
                        $data["theme"] =  "invite";
                        $data['password'] = null;
                        Notification::route('mail', $data)->notify(new MailSender($data, $data["email"]));
                    }
                    $this->msg = ['code' => 200 , 'msg' => 'Invite was sent' , 'type' => 'success'];

        }else {
            $this->msg = ["code" => '206' , 'msg' => 'This User was invited' , 'type' => 'error' ];
        }
        return  $this->msg;

    }

    /** method to create user in local database
     * @param array $data
     * @return array
     */

    public function create(array $data) : array{
        if($this->checkData($data)){
            $user = auth()->user();
            $team_name  = Str::random();
            if(isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }
                $data['status'] = 1;//active
                $data['is_enabled'] = true;

            if(!isset($data['role'])){
                $data['role'] = 2;
            }

            $this->msg = $this->user->create_or_update_local($data);
                if($data['firm']['main_firm'] == true){
                    $team_name = $data['firm']['company_name'] ?? $data['firm']['name'];
                }

                 if(isset($data['firm']) && $data['firm']['name'] !== null  ) {
                     $data['firm']['company_name'] = $data['firm']['name'];
                     $firm_uses_name  = $this->firm->findByName($data['firm']['company_name']);
                     if($firm_uses_name->count() > 0 ){
                         $firm =  $this->firm->getById($data['firm']['id']);
                         $data['firm']['company_name'] = $data['firm']['name'];
                         $firm->update($data['firm']);
                     }else
                     $firm = $this->firm->create($data['firm']);
                     if (isset($data['firm']["banks"])) {
                         foreach ($data['firm']["banks"] as $bank) {
                             if (isset($bank['bank_name']) & $bank['bank_name'] !== null) {
                                 if(!isset($bank['id']))
                                  BankDetails::create(array_merge($bank, ['firm_id' => $firm->id ]));
                                else  BankDetails::where('id' , $bank['id'] )->update($bank);
                             }
                         }
                     }
                     if(isset($data['firm']['vat'])){
                         foreach ($data['firm']["vat"] as $vat) {
                             if(!isset($vat['id'])) FirmsVat::create(array_merge($vat, ['firm_id' => $firm->id ]));
                             else FirmsVat::where('id' , $vat['id'])->update($vat);
                         }
                     }
                 }

                Switcher::useDefaultDB();


            if($this->msg["code"] === 200){

                $user->team()->first()->update([ 'currency'=> 'EUR' , 'timezone' => $data['timezone'] ?? null , 'name' => $team_name ??
                    $data['firstname'] .' '. $data['lastname'] ]);
                $user->update(["local_id" => $this->msg['user']['id']]);
                if(isset($data['password']) && $user->password()){
                    $user->password()->delete();
                }
            }
        }
        return  $this->msg;
    }

    /** method to update local user
     * @param $id
     * @param $data
     * @return array
     */

    public function update(int $id , array $data): array {
        if(isset($data['avatar']) && strlen($data['avatar']) > 50){
          $path = $this->image->store_image($data['avatar'] , "/images/users/" . auth()->user()->id . '/');
          $data['avatar'] = $path;
        }

        if($this->user_local->update($id,  $data)){
            $this->msg= ['code' => 200 , 'msg' => 'User Updated.'];
        }else{
            $this->msg = ['code' => 500 , 'msg' => 'Oops.. something was wrong.'];
        }
        return $this->msg;
    }

    /** method to update user password
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update_password(int $id , array $data) : array{
        $user = $this->user_local->getById($id);
        $res = $this->checkPasswordOnUniq($user, $data['password_old']);
        if ($res['code'] == 200) {
                 $user->update(['password' => bcrypt($data['password'])]);
                $this->msg = ['code' => 200 , 'msg' => 'Password updated' , 'type' => 'success'];
        }else  $this->msg  =  $res;
        return $this->msg;
    }


    /** method to delete
     * @param $id
     * @return array
     */
    public function delete( int $id) : array{
        $user = $this->user_local->getById($id);
        if($user){
            $user_email= $user->email ;
            if(!$user->has_multi){
                //if user doesn't have multi account delete everywhere
                if($user->bills()->count() >= 1) $this->msg  = ['code' => 200 , 'msg' => 'Oops : You cant to delete that user. First delete invoices.' , 'type' => 'error' ];
                else if($user->bills_act()->count() >= 1)  $this->msg  = ['code' => 200 , 'msg' => 'Oops : You cant to delete that user. First remove responsibility. ' , 'type' => 'error' ];
                else {
                    $user->delete();
                    //Switcher::useDefaultDB();
                    //$this->user->getByEmail($user_email)->delete();
                    $this->msg  = ['code' => 200 , 'msg' => 'User deleted' , 'type' => 'success' ];
                }
            }else{
                //if user has_multi account delete ONLY from local Database
                $user->delete();
                $this->msg  = ['code' => 200 , 'msg' => 'User deleted' , 'type' => 'success' ];
            }

        }else{
            $this->msg = ['code' => 500 , 'msg' => 'Oops: User not found'  , 'type' => 'error' ];
        }
         return  $this->msg;
    }

    /** method to get users with permission
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */

    public function getAllWithPermission(){
        return $this->user->getAllWithPermission();
    }

}
