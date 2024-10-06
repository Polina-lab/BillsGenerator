<?php

namespace App\Http\Controllers\Admin\Bills;

use App\Http\Requests\Order\CreateRequest;
use App\Http\Requests\HasDigitIdRequest;
use App\Http\Requests\Order\GetChartData;
use App\Http\Requests\Order\SearchRequest;
use App\Http\Requests\Order\UpdateRequest;
use App\Http\Resources\ArrayTranducerWData;
use App\Services\Bills\OrderService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    protected  $orders ;

    public function __construct(OrderService $o){
        $this->orders = $o;
    }

    public function getAll(Request $request){
       return $this->orders->getAll($request->all());
    }

    public function getById(HasDigitIdRequest  $request){
        return $this->orders->getById($request->id);
    }

    public function search(SearchRequest $request){
        return  new ArrayTranducerWData($this->orders->search($request->all()));
    }

    public function create(CreateRequest $request){
        return new ArrayTranducerWData($this->orders->create($request->all()));
    }

    public function update(HasDigitIdRequest $id , UpdateRequest $request){

        return new ArrayTranducerWData($this->orders->update($id->id , $request->all()));
    }

    public function delete(HasDigitIdRequest $id){
        return new ArrayTranducerWData($this->orders->delete($id->id));
    }

    public function getChartData(HasDigitIdRequest  $id){
        return  new ArrayTranducerWData($this->orders->getCharts( (int)$id->id, $id->all()));
   }

}
