<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Bills\CreateCompanyRequest;
use App\Http\Requests\Bills\UpdateCompanyRequest;
use App\Http\Requests\HasDigitIdRequest;
use App\Http\Resources\ArrayTranducerWData;
use App\Http\Resources\ArrayTransducer;
use App\Repositories\Bills\CompanyRep;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
    protected $company;

    public function __construct(CompanyRep  $c){
        $this->company = $c;
    }

    public function getById(HasDigitIdRequest $id){
        return $this->company->getById($id->id);
    }

    public function create(CreateCompanyRequest  $request){
        if($this->company->getByName($request->get('name')) > 0){
            $res = ["code" =>  500, "msg" => "Oops: " , "type" =>"error"];
        }else{
            $res = $this->company->create($request->all());
        }

        return new ArrayTranducerWData($res) ;
    }

    public function update(UpdateCompanyRequest $request){
        if($this->company->update($request->all())){
            $msg=  ["code" =>  200, "msg" => "Ok" , "type" =>"success"];
        }else
            $msg=  ["code" =>  500, "msg" => "Oops: " , "type" =>"error"];
        return  new ArrayTransducer($msg);
    }

    public function delete(HasDigitIdRequest $id){
        $msg = ["msg" => "Company deleted" , "code"=> 200 , "type" => "success"];
        try{
            $this->company->delete($id->id);
        }catch (\Exception $e){
            $msg = ["msg" => "Ooops:" . $e->getMessage() , "code"=> 500 , "type" => "error"];
        }
        return new ArrayTransducer($msg);
    }




}
