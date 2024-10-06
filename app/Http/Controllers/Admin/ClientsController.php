<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\HasDigitIdRequest;
use App\Http\Requests\Clients\CreateRequest;
use App\Http\Resources\ArrayTranducerWData;
use App\Http\Resources\ArrayTransducer;
use App\Http\Resources\Clients\findByResource;
use App\Http\Resources\Clients\getClientsResource;
use App\Services\ClientService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Clients\SelectRequest;

class ClientsController extends Controller
{

    protected $client ;

    public function __construct(ClientService  $c){
        $this->client = $c;
        $this->msg = [];
    }

    public function findBy(Request $request) {
       if($request->has('name')) $res  = new  findByResource($this->client->findBy($request));
       else $res = new ArrayTranducerWData($this->client->findByClient($request));
       return $res;
    }

    public function getAll(Request  $request){
        return new getClientsResource($this->client->getAll($request));
    }

    public function getById( HasDigitIdRequest $id){
        return $this->client->getById($id->id);
    }

    public function create(CreateRequest $request){
        return new ArrayTranducerWData($this->client->create($request->client));
    }

    public function update(HasDigitIdRequest $id  ,CreateRequest  $request){
        return new ArrayTranducerWData($this->client->update(  $id->id, $request->client));
    }

    public function delete(HasDigitIdRequest $id){
        return $this->client->delete($id->id);
    }

    public function deleteList( SelectRequest  $request){
        foreach($request->selected as $del){
           $this->msg = $this->client->delete($del);
        }
        return new ArrayTransducer($this->msg);
    }


}
