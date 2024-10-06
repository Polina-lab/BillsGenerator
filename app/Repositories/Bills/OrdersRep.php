<?php


namespace App\Repositories\Bills;

use App\Models\Bills\Orders;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;

class OrdersRep
{

    public $order;

    public function __construct(Orders  $o){
        $this->order  = $o;
    }

    public function getAll(array  $data){
        return $this->order->with("firms")->when(isset($data['name']), function (Builder $query) use ($data) {
                $query->where("name",'LIKE', "%". $data['name'] ."%");
            })->when(isset($data['firm']) , function (Builder $query) use ($data){
                $query->where('firms_id' , $data['firm']);
            })->when(isset($data['category']) , function(Builder $query) use ($data){
                $query->where('categories_id' , $data['category']);
            })->when(isset($data['orderBy']) && isset($data['orderType']) , function(Builder $query) use ($data){
                $query->orderBy($data['orderBy'] , $data['orderType']);
            })->paginate(isset($data['per_page']) ? $data["per_page"] : 15 );
    }

    public function search(array  $data){
        $res =  $this->order->where('name' ,"like" ,  '%'. $data['name']. '%' )->get();

        if($res->count() ==  0){
            $res  = ['data' => [] , 'msg' => "Not found" , 'code' => 404  , 'type'=> 'error'];
        }
        else $res = ['data' => $res , 'msg' => "Ok" , 'code' => 200  ,  'type'=> 'success' ];
        return $res;
    }

    /** this method to get order by Id
     * @param $id
     * @return mixed
     */

    public function getById(int $id){
       return $this->order->where("id" ,$id )->with('categories' , 'firms')->first();
    }

    /**
     * @param int $id
     * @return mixed
     */

    public function getByIdWithBills( int $id){
        return $this->order->whereId($id)->with('bills')->first();
    }



    /** this method to create order
     * @param $data
     * @return mixed
     */

    public function create($data){

        if(!isset($data["unit_price"])) $data["unit_price"] = 0;
        if(!isset($data["amount"])) $data['amount'] = 1;
        if(!isset($data["desc"])) $data['desc'] = '';
        if(!isset($data["name"])) $data['name'] = '';

        return $this->order->create($data);
    }

    /** this method to update order
     * @param $id
     * @param $data
     * @return mixed
     */

    public function update(int $id , array $data){
        $order =  $this->getById($id);

       // if(isset($data["has_KM"])) {
       //  if we change summ with KM
       //     if ($data["has_KM"] & $order->unit_price * 1.2 !== $data["unit_price"]) {
       //         $data["unit_price"] = $order->unit_price;
       //    }
       // }
        return $order->update($data);
    }

    /** this method to delete order
     * @param $id
     * @return mixed
     */

    public function delete($id){
        return $this->order->where("id" , $id)->delete();
    }


}
