<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\RegisterRequest;
use App\Http\Requests\Users\LoginRequest;
use App\Http\Resources\ArrayTransducer;
use App\Services\UserService;
use TheSeer\Tokenizer\Exception;

class AuthController extends Controller
{
    public $user;
    public $msg = [];

    public function __construct(UserService $u){
        $this->user = $u;
    }

    /** register
     * @param RegisterRequest $request
     * @return ArrayTransducer
     */

    public function register(RegisterRequest $request) : ArrayTransducer{
          return new ArrayTransducer($this->user->register($request->all()));
    }

    /** login
     * @param LoginRequest $request
     * @return ArrayTransducer
     */

    public function login(LoginRequest $request): ArrayTransducer{
            $msg =  $this->user->login($request);
            if(isset($msg["token"])) {
                $this->msg = array_merge($msg , ["code" => 200, "token_type" => "Bearer"]);
            }else $this->msg = $msg;

        return new ArrayTransducer($this->msg);
    }

    public function reset_password(RegisterRequest $request) : ArrayTransducer{
        $this->msg  =  $this->user->reset_password($request->all());
        return new ArrayTransducer($this->msg);
    }



    /**  logout
     * @return ArrayTransducer
     * 200 - ok
     * 500 - tokens not found
     */

    public function logout() : ArrayTransducer{
        try {
            request()->user()->currentAccessToken()->delete();
            $this->msg = ['code' => 200, "msg" => "Bye !", "status" => "success"];
        }catch(\Exception $exception){
                info($exception->getMessage());
                $this->msg = ['code' => 500 , "msg" => "Oops: user tokens not found","status" => "error"  ];
        }
            return  new ArrayTransducer($this->msg);
    }

    /** verif
     * @param $slug
     * @return ArrayTransducer
     */

    public function verif(string $slug) : ArrayTransducer{
        return new ArrayTransducer($this->user->verif($slug));
    }

}
