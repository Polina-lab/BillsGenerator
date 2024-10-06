<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Facade\Switcher;
use App\Http\Controllers\Controller;
use App\Http\Requests\HasDigitIdRequest;
use Illuminate\Http\Request;
use App\Repositories\User\UserRep;
use Illuminate\Support\Collection;

class RemoteUsersController extends Controller
{

    public $user;

    public function __construct(UserRep $u){
        $this->user = $u;
    }

    /**
     * This request to get all users from remote host
     */
    public function index(Request  $request){
        $buyers =  $this->user->getBuyers(); //
        $users = $this->user->getAll();
     //   $result['users_all'] =  $users;
        $result['users_count'] = $users->count();
        $result['buyers_count'] = $buyers->count();
        $result['buyers'] = [];
        $team_count = 0;
        foreach ($buyers as $buyer) {
            $result['buyers'][$buyer->email] = [];
            $database = [];
            foreach($buyer->team as $team) {
                   if($team->has_created === 1) {
                       Switcher::useLocalDB($team->database);
                       array_push( $database, $team->database);
                       $result['buyers'][$buyer->email][$team->database]['users'] = $this->user->getAll();
                       $result['buyers'][$buyer->email]['teams'][] = $team;
                   }
            }
            $result['buyers'][$buyer->email]['database'] = $database;
        }

        $result['active_team_count'] = $team_count;
        return $result;
    }


    /** this route to delete team
     * @param HasDigitIdRequest $id
     */

    public function delete_team(HasDigitIdRequest  $id){
        dd($id);
    }




}
