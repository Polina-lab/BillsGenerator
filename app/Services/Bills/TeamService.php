<?php


namespace App\Services\Bills;


use App\Repositories\Team\TeamRep;

class TeamService
{
    protected $team;

    public function __construct(TeamRep $t)
    {
        $this->team = $t;
    }

    /** get all Teams
     * @return \App\Models\Users\Team[]|\Illuminate\Database\Eloquent\Collection
     */

    public function getAll(){
        return $this->team->getAll();
    }

    /**
     * @return mixed
     */

    public function create(){
        return $this->team->create_new_team();
    }

    public function update(){

    }

    public function index(){


    }


}
