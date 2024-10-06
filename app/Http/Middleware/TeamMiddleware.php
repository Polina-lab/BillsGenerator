<?php

namespace App\Http\Middleware;

use App\Models\Users\Team;
use App\Repositories\Team\TeamRep;
use Closure;
use Illuminate\Http\Request;

class TeamMiddleware
{

    protected $team;

    public function __construct(TeamRep $t)
    {
        $this->team = $t;
    }


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if(isset($request->segments()[1])){
             $c_team =  $this->team->getByKey( $request->segments()[1]);
             $this->team->switchDB($c_team);
             $request->attributes->add(['CURRENT_TEAM_LOCAL' => $c_team]);
        }else{
            return ['code' => 500 ,"msg" => "Oops: cant' to choose database in middleware " ];
        }

        return $next($request);
    }
}
