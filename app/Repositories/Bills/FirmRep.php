<?php

namespace App\Repositories\Bills;
use App\Models\Bills\Firms;

class FirmRep
{

    public $firm;
    public function __construct( Firms $f){
        $this->firm = $f;
    }

    public function getById($id){
        return $this->firm->where("id" , $id)->first();
    }
}
