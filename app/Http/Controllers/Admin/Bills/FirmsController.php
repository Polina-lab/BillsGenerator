<?php

namespace App\Http\Controllers\Admin\Bills;

use App\Http\Requests\Firm\CreateRequest;
use App\Http\Requests\Firm\UpdateRequest;
use App\Http\Requests\HasDigitIdRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArrayTranducerWData;
use App\Http\Resources\Firms\getFirmResource;
use App\Services\Bills\FirmsService;
use Illuminate\Http\Request;

class FirmsController extends Controller
{

    protected $firm;

    public function __construct( FirmsService $f)
    {
        $this->firm = $f;
    }

    public function getAll(Request  $request){
        return new getFirmResource(!$request->has("all") ? $this->firm->getActive() :  $this->firm->getAll());
    }

    public function getAll_represetatives(){
        return new ArrayTranducerWData($this->firm->getAllRepresentatives());
    }

    public function create(CreateRequest $request){
        return new ArrayTranducerWData($this->firm->create($request));
    }

    public function update(HasDigitIdRequest  $id , UpdateRequest $request){
        return new ArrayTranducerWData($this->firm->update($id->id , $request));
    }

    public function delete(HasDigitIdRequest $id){
        return new ArrayTranducerWData( $this->firm->delete($id->id));
    }

    public function delete_bank(HasDigitIdRequest $id){
        return new ArrayTranducerWData($this->firm->delete_bank($id->id));
    }

}
