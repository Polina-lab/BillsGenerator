<?php


namespace App\Repositories;


use App\Models\Coupons;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use function Symfony\Component\Translation\t;

class CouponsRep
{
    protected $coupons;
    protected $msg;

    public function __construct(Coupons $c){
        $this->coupons = $c;
        $this->msg  = [];
    }

    public function getAll($request){
        return $this->coupons->paginate($request->has("pageSize") ? $request->get("pageSize") : 15);
    }


    public function getById(int $id) : Coupons{
        return $this->coupons->where("id" , $id)->first();
    }

    protected function getResult(){


    }


    public function getByCode($code){
        $coupon =  $this->coupons->where("code" , $code)->first();
        if($coupon) {
            if(!$this->checkCouponByDate($coupon)){
                $this->msg = ['code' => '206' , 'msg' => 'This coupon expired' , 'type' => 'error'];
            }else if($coupon->usages_remaining < 1){
                $this->msg = ['code' => '207' , 'msg' => 'This coupon has been used ( ' . $coupon->usages_remaining  .')' , 'type' => 'error'];
            }else{
                $this->msg = ["code" => "200", "data" => [ 'amount_percent'  => $coupon->amount_percent ,
                    'amount_eur' => $coupon->amount_eur ] , "type" => "success"];
            }
        }else $this->msg = ["code" => "404" , "msg" => "Oops" , "type" => "error"];
        return $this->msg;
    }


    /**
     * @param Coupons $coupon
     * @return false|string
     */

    protected function checkCouponByDate (Coupons $coupon){
        if($coupon->end_date != null) {
            $diff = Carbon::now()->startOfDay()->diffInDays(new Carbon($coupon->end_date), false);
            if ($diff >= 1) $res = $diff;
            else $res = false;
        }else {
            $res = false;
        }
        return $res;
    }


    public function create(array  $data) : array{
        if($this->coupons->create($data))
            $this->msg = ["code" => "200" , "msg" => "Created" , "type" => "success"];
        else $this->msg = ["code" => "500" , "msg" => "Oops" , "type" => "error"];
        return $this->msg;
    }


    public function update($id  , $data) : array{
        $coupon = $this->getById($id);
        if($coupon) {
            if ($coupon->update($data))
                $this->msg = ["code" => "200", "msg" => "Updated", "type" => "success"];
            else $this->msg = ["code" => "500", "msg" => "Oops ", "type" => "error"];
        }else $this->msg = ["code" => "404", "msg" => "Oops: coupon not found", "type" => "error"];
        return $this->msg;

    }

    public function delete(int $id) : array{
        $coupon = $this->getById($id);
        if($coupon) {
            if ($coupon->delete())
                $this->msg = ["code" => "200", "msg" => "Deleted", "type" => "success"];
            else $this->msg = ["code" => "500", "msg" => "Oops : ! ", "type" => "error"];
        }else $this->msg = ["code" => "404", "msg" => "Oops: coupon not found", "type" => "error"];
        return $this->msg;
    }

}
