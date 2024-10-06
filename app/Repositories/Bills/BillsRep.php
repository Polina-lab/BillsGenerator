<?php


namespace App\Repositories\Bills;
use App\Models\Bills\Bills;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class BillsRep
{

    protected  $bills;
    public function __construct( Bills $b)
    {
        $this->bills = $b;
    }


    /** Method to get all booking bills
     * @param array $request
     * @return array|Builder[]|\Illuminate\Database\Eloquent\Collection
     */

    public function getAllBooking(array $request){
        return $this->bills->with(['firms', "users", "orders"])
            ->when((isset($request['incoming']) && $request['incoming'] == true) , function(Builder $query){
                $query->where('type' , false );
                //true = outcoming , false = incoming
            })->when(!isset($request['incoming']) , function(Builder $query) {
                $query->where('type', true);
            })
            ->when(isset($request["firm"]) ,  function (Builder $query ) use ($request){
                $query->where("firm_id" , $request["firm"]);
            })->where("deal", 2)->get();
    }

    /** Method to get last number
     * @param $data
     * @return mixed
     */

    public function countUseNumber($data){
        return $this->bills->where("number" , $data["number"])->where("firm_id" , $data["firm"] )->whereMonth("date" , $data["month"])->whereYear("date" , $data["year"])->count();
    }

    /** Method to get all by request
     * @param array $request
     * @return Bills[]|array|Builder[]|\Illuminate\Database\Eloquent\Collection
     */

    public function getAllWithRequest( array $request){
        return $this->bills->with(['firms', "users" , "orders"])
            ->when((isset($request['incoming']) && $request['incoming'] == true) , function(Builder $query){
                $query->where('type' , false );
                //true = outcoming , false = incoming
            })->when((!isset($request['incoming']) || $request['incoming'] == false)  , function(Builder $query) {
                $query->where('type', true);
            })->when(isset($request['firm'])  , function (Builder $query ) use ($request){
                $query->where("firm_id" , $request["firm"] );
            })
            ->when(isset($request['user']) , function (Builder $query ) use ($request){
                $query->where("act_user_id" , $request["user"] );
            })
            ->when(isset($request['id']) , function (Builder $query ) use ($request){
                $query->whereId( $request["id"] );
            })
            ->when(isset($request['number']) , function (Builder $query ) use ($request){
                $query->where("number" ,'LIKE' ,   $request["number"] . '%'  );
            })
            ->when(isset($request['invoice']) , function (Builder $query ) use ($request){
                $query->where("invoice" ,'LIKE' , $request["invoice"].'%' );
            })
            ->when(isset($request['name']) , function (Builder $query ) use ($request){
                $query->whereHas('orders', function( Builder $q) use ($request) {
                    $q->where("orders.name" ,"LIKE", "%" . $request["name"] . "%" );
                    return $q;
                });
            })
            ->when(isset($request['price']) , function (Builder $query ) use ($request){
                $query->where("price" , ">=" , $request["price"] );
            })
            ->when(isset($request['status']) , function (Builder $query ) use ($request){
                $query->where("status" , $request["status"] );
            })
            ->when(isset($request["month"]), function (Builder $query) use ($request){
                $query->whereMonth("date" , $request["month"]);
            })
            ->when(isset($request["deal"]), function (Builder $query) use ($request){
                $query->where("deal" , $request["deal"]);
            })
            ->when( isset($request["year"]) , function(Builder $query) use ($request){
                $query->whereYear("date" , $request["year"]);
            })
            ->when(isset($request['client']), function(Builder  $query) use ($request){
                $query->where('client_id' , $request['client']);
            })
            ->when(isset($request['date']) , function (Builder $query ) use ($request) {
                $query->whereDate("date", Carbon::parse($request["date"]));
            })->when((isset($request['orderBy']) && isset($request['orderType']))   , function (Builder $query) use ($request){
               return $query->orderBy($request['orderBy'] , $request['orderType']);
            })->when((!isset($request['orderBy']) || !isset($request['orderType'])) , function (Builder $query) use ($request){
                return $query->orderBy("number" , "ASC");
            })->get();
    }

    /** Method to get Simple bill
     * @param $id
     * @return mixed
     */

    public function getSimpleBill($id){
        return $this->bills->whereId($id)->first();
    }


    /** Method to get bill by ID with "firms" and "users"
     * @param $id
     * @return mixed
     */

    public function getById($id){
        return $this->bills->whereId($id)->with(["firms" , "users"])->first();
    }

    /** Method to get bill by Id with all relations
     * @param $id
     * @return mixed
     */

    public function getByIdFull($id){
        return $this->bills->whereId($id)->with(["firms", "firms.representatives", "firms.banks" , "users", "user_act", "sender_user", "orders" , "clients" , "companies"])->first();
    }



    /** Method to update bill when was paid
     * @param $id
     * @return mixed
     */

    public function changeWasPaid($id){
        return $this->bills->whereId($id)->update(['wasPaid' => Carbon::now()]);
    }

    /** protected Method to get bills by request(firm_id , mouth , year)
     * @param array $request
     * can use incoming
     * @return mixed
     */

    protected function requestByFYM(array $request){
        return  $this->bills->when( isset($request["firm"]), function (Builder $query) use ($request) {
            $query->where("firm_id", $request["firm"]);
        })->when(isset($request["month"]), function (Builder $query) use ($request) {
            $query->whereMonth("date", $request["month"]);
        })
        ->when(!isset($request['incoming']) , function (Builder $query) use ($request) {
            $query->where('type' , true);
        })
        ->when(isset($request['incoming']) , function (Builder $query) use ($request){
            $query->where('type' , false );
        })
        ->when(isset($request["year"]), function (Builder $query) use ($request) {
                $query->whereYear("date", $request["year"]);
        });
    }


    /** method to get Numbers from requestByFYM
     * @param $request
     * @return mixed
     */

    public function getNumbers($request){
        return  $this->requestByFYM($request)->orderBy("number", "ASC")->pluck("number");
    }

    /** method to get last Number from requestByFYM
     * @param $request
     * @return mixed
     */

    public function getLastNumber($request){
        $numbers  = $this->getNumbersList($request)->sort();
        $count  = $numbers->count();
        $last_el = $numbers->pop();
        if($last_el > $count ){
            for( $i = 1 ; $i < $last_el ; $i++ ){
                if(!$numbers->contains($i)){
                    return $i;
                }
            }
        }
        else return $last_el;
    }

    public function getNumbersList($request){
        return $this->requestByFYM($request)->pluck('number');
    }



    /** method to get count bills by year and month
     * @param $month
     * @param $year
     * @return mixed
     */

    public function countByYearAndMonth($month , $year ){
        $this->bills->whereYear("date", $year)->whereMonth("date", $month)->delete();
        return $this->bills->whereYear("date", $year)->whereMonth("date", $month)->count();
    }

    /** method to get bills by year, mount and status
     * @param $month
     * @param $year
     * @param bool $status
     * @return mixed
     */

    public function getByYearAndMonth($month , $year , $status = true ){
        return $this->bills->whereYear("date", $year)
            ->whereMonth("date", $month)
            ->whereStatus($status)->get();
    }

    /** method to get bills with relations( users , firms ) by month , year and status
     * @param $month
     * @param $year
     * @param bool $status
     * @return Bills[]|Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */

    public function getFullBills($month , $year , $status = true){
        return $this->bills->with(["users", "firms"])->whereYear("date", $year)
            ->whereMonth("date", $month)
            ->where("status" ,$status)->get();
    }

    /** method to get last bill by mount and year
     * @param int $month
     * @param int $year
     * @return mixed
     */

    public function getLastBill( int $month , int $year){
        return  $this->bills->whereYear("date", $year)
            ->whereMonth("date", $month)
            ->orderBy("number" , "DESC")->first();

    }

    /** method to create new bill
     * @param $data
     * @return mixed
     */

    public function create($data){
        if(isset($data["orders"][0]["name"]) || isset($data["name"])) {
            $data["name"] = $data["orders"][0]["name"] ?? $data["name"];
            $data["user_id"] = $data["user_id"] ?? auth()->user()->local_id;
            $data["date"] = isset($data['date']) ? $this->checkDateFormat($data["date"]) : Carbon::now()->format('Y-m-d');
            $data["price"] = $data["price"] ?? 0;
            $data["currency"] = $data["currency"] ?? "EUR";
            return $this->bills->create($data);
        }else {
            throw new \Exception("Order name not found .");
        }
    }


    /** check date format
     * @param string $dateInp
     * @return string
     */

    protected function checkDateFormat( string $dateInp) : string{
        try {
            $date = Carbon::parse( $dateInp)->format("Y-m-d");
        }catch (\Exception $e){
            $date = Carbon::now()->format("Y-m-d");
        }
        return $date;
    }

    /** method to update bill
     * @param Bills $bill
     * @param array $data
     * @return array
     */

    public function update(Bills  $bill ,   $data) : array{
        $resp = [ "status" => false , "msg" => "Oops: "];
                try {
                       $res = [
                        'number' => $data["number"],
                        'firm_id' => $data["firm_id"],
                        'user_id' => $data["user_id"],
                        "act_user_id" => $data["act_user_id"],
                        'date' => isset($data['date']) ? $this->checkDateFormat($data["date"]) : Carbon::now()->format('Y-m-d'),
                        'status' => $data["status"] ?? 1,
                        'deal' => $data["deal"] ?? null,
                        'deadline' => $data['deadline'] ?? null,
                        'prepaid' => $data['prepaid'] ?? null,
                        'penalty' => $data['penalty'] ?? null,
                        'paid_cash' => $data['paid_cash'] ?? null,
                        'locale' => $data['locale'] ?? 1,
                        'sender_user_id' => $data['sender_user_id'] ?? null,
                        'was_sent' => $data["was_sent"] ?? null,
                        "client_id" => $data["client_id"] ?? null,
                        "company_id" => $data["company_id"] ?? null,
                        "payment_method" => $data["payment_method"] ?? 0,
                        "vat_id" => $data["vat_id"] ?? null,
                        "was_paid" => $data["was_paid"] != null ? $this->checkDateFormat($data["was_paid"]) : null,
                        "updated_at" => Carbon::now(),
                        "comment" => $data["comment"] ?? "",
                    ];
                    if(isset($data['invoice']))
                        $res =  array_merge($res ,["invoice" => $data['invoice']]);

                    $resp["status"] = $bill->update($res);
                    $resp["msg"] = "The bills updated";
                }catch (\Exception $e){
                    info($e->getMessage());
                    $resp["msg"] .= $e->getMessage();
                }

        return $resp;
    }

    /** method to delete bill
     * @param $id
     * @return mixed
     */

    public function delete($id){
        $bill = $this->getSimpleBill($id);
        if($bill->orders){
            $bill->orders()->delete();
        }
        return $bill->delete();
    }


}
