<?php


namespace App\Services\Bills;


use App\Http\Resources\ArrayTransducer;
use App\Repositories\Bills\OrdersRep;
use Carbon\Carbon;

class OrderService
{
    protected $orders ;
    public $msg;

    public function __construct(OrdersRep $o){
        $this->orders = $o;
        $this->msg = [];
    }

    public function getAll(array $resp){
        return $this->orders->getAll($resp);
    }

    public function getById(int $id){
        $res = $this->orders->getById($id);
        if($res == null){
            return  new ArrayTransducer(['msg' => 'Oops : This order not found' , 'code' => 404  ]);
        }else return $res;
    }

    public function create(array $resp) : array{
        if($this->orders->create($resp)){
            $this->msg = ['msg' => 'Order created' , 'code' => 200 ];
        }else{
            $this->msg = ['msg' => 'Oops : Something was wrong' , 'code' => 500  ];
        }
        return $this->msg;
    }

    public function update(int $id  , array  $resp) : array{
        if($this->orders->update($id , $resp)){
            $this->msg = ['msg' => 'Order updated' , 'code' => 200  ];
        }else {
            $this->msg = ['msg' => 'Oops : Something was wrong' , 'code' => 500  ];
        }
        return $this->msg;
    }

    public function delete(int $id): array{
        if($this->orders->delete($id)){
            $this->msg = ['msg' => 'Order deleted' , 'code' => 200  ];
        }else {
            $this->msg = ['msg' => 'Oops : Something was wrong' , 'code' => 500  ];
        }
        return $this->msg;
    }

    /**
     * @param array $data
     * @return array
     */


    public function search(array $data){
        return $this->orders->search($data);
    }

    /**
     * @param int $order_id
     * @param array $data
     * @return array
     */


    public function getCharts(int $order_id  , array  $data){
        $order = $this->orders->getByIdWithBills($order_id);
        $this->msg =['code' => 204 , 'msg' => "Content not found" ];
        if(!isset($data['start'])  || !isset($data['end'])) {
            $current_year = date("Y");;
            $start =  $current_year. '-01-01';
            $end = date('Y-m-d');
        }else {
            $start = $data['start'];
            $end = $data['end'];
        }

        if($order && $order->bills()->count() > 0){
           $result =  $order->bills()->whereBetween('date' , [$start , $end])
               ->orderBy('date', "ASC")
               ->get(['date'  , 'price' , 'amount'])
               ->groupBy('date')
               ->toArray();
           if(sizeof($result) > 0 ){
               $this->msg = ['code' => 200 , 'data' => $result];
           }
        }
        return $this->msg;
    }

}
