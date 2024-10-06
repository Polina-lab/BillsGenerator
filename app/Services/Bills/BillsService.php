<?php


namespace App\Services\Bills;
use App\Repositories\Bills\BillsRep;
use App\Repositories\Bills\FirmRep;
use App\Repositories\Bills\OrdersRep;
use App\Traits\CheckRole;
use App\Traits\GenerateBillNumberTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BillsService
{
    protected $bills;
    protected $msg;
    protected $client;
    protected $order;
    protected $firm;
    protected $client_id = null;
    protected $company_id = null;
    use GenerateBillNumberTrait;
    use CheckRole;
    const PERMISSION_DENIED = ['code' => 403 ,"msg" => "Permission denied" , "status" => "error"];


    public  function  __construct( BillsRep $b  , ClientService $c , FirmRep $f, OrdersRep  $o){
        $this->bills = $b;
        $this->client = $c;
        $this->order = $o;
        $this->firm = $f;
        $this->msg = ["code" => 200 , "msg" => "Bill Created" , "type" => "success"] ;
    }

    /** to get with request
     * @param $request
     * @return array|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */

    public function getAllWithRequest($request){
        return  $this->bills->getAllBooking($request->all())->merge($this->bills->getAllWithRequest($request->all()));
    }

    /** to get by Id
     * @param $id
     * @return mixed
     */

    public function getById($id){
        return $this->bills->getById($id);
    }

    /** to get bill with full info
     * @param $id
     * @return mixed
     */

    public  function getByIdFull($id){
        return $this->bills->getByIdFull($id);
    }

    /** to change was_Paid
     * @param $id
     * @return array
     */

    public function changeWasPaid($id){
        $bill = $this->bills->getSimpleBill($id);
        if(auth()->id() === $id || $this->check_role('mark-bill-paid')) {
            $this->bills->changeWasPaid($bill);
            $this->msg =  ["code" => 200 , "msg" => "Bill Changed" , "type" => "success"];
        }
        else $this->msg =  self::PERMISSION_DENIED;
        return $this->msg;
    }


    /** Function to clone bill
     * @param int $id
     * @return array
     */

    public function clone(int $id) : array{
        $bill = $this->bills->getSimpleBill($id);
        if( $this->check_role("clone-bill")) {
            if($bill) {
                $new_bill = $bill->replicate();
                $new_bill->date = Carbon::now()->format('Y-m-d');
                $data = $this->creating_request_for_get_number(['date' => now(), 'firm_id' => $bill->firm_id]);
                $new_bill->number = $data['number'];
                $new_bill->invoice = $data['invoice'];
                $new_bill->push();
                $orders = $bill->orders->pluck('id');
                $new_bill->orders()->attach($orders);
                $this->msg["msg"] = "Bill cloned";
                $this->msg["data"] = $this->getByIdFull($new_bill->id);
            }else{
                $this->msg = ['msg' => "Bill not found", 'code' => '404'];
            }
        }else
            $this->msg = self::PERMISSION_DENIED;
        return $this->msg;
    }



    /** Function for creating bill
     * @param int $id
     * @param array $data
     * @return array
     */

    public function update(int $id ,array $data): array
    {
            $bill = $this->bills->getSimpleBill($id);
            if ( auth()->id() === $bill->user_id || auth()->id() === $bill->act_user_id ||  $this->check_role("edit-bill")) {
                if($bill) {
                    if($bill->firm_id !== $data['firm_id']){
                        $res =  $this->creating_request_for_get_number($data);
                        $data =  array_merge($data , $res);
                        if(isset($data['invoice'])) $data =   array_merge($data  ,["invoice" => $data['invoice']]);
                    }
                    $this->createOrUpdateClientOrCompany($data);
                    $data["client_id"] = $data['client_id'] ?? $data["clients"]["id"] ?? $this->client_id;
                    $data["company_id"] = $data['company_id'] ?? $data["companies"]["id"] ?? $this->company_id;
                    if( isset($data['date']) &&  gettype($data['date']) === 'string')
                        $data['date'] = Carbon::parse($data['date'])->format('Y-m-d');


                    if( !isset($data['was_paid']) ||  gettype($data['was_paid']) !== 'string')
                        $data['was_paid'] = null;
                    else Carbon::parse($data['was_paid'])->format('Y-m-d');

                    $has_updated = $bill->update($data);
                    $orders_id = [];
                    if ($has_updated) {
                        if($data['orders']) {
                            foreach ($data['orders'] as $order) {
                                if (isset($order["id"])) {
                                    $this->order->update($order["id"], array_merge($order, [
                                        "desc" => $order['desc'] ?? "" ]));
                                    $amount = $order['amount'] ?? 1;
                                    $orders_d['amount'] = $amount;
                                    $orders_d['price'] = ($order['unit_price'] ?? 0) * $amount;
                                    $orders_id[$order["id"]] =   $orders_d ;
                                } else {
                                    $c_order = $this->order->create(array_merge($order, ['bills_id' => $id]));
                                    $amount = $order['amount'] ?? 1;
                                    $orders_d['amount'] = $amount;
                                    $orders_d['price'] = ($order['unit_price'] ?? 0) * $amount;
                                    $orders_id[$c_order->id] =  $orders_d ;
                                }
                            }
                            $bill->orders()->sync($orders_id );
                        }
                        $this->msg = ["code" => 200, "msg" => "Bills updated", "type" => "success"];
                    } else $this->msg = ["code" => 206, "msg" => "Oops : something wrong", "type" => "error"];
                }else $this->msg = ["code" => 404, "msg" => "Oops: Bill not found", "type" => "error"];
            }else $this->msg = self::PERMISSION_DENIED;
        return $this->msg;
    }

    /** Protected function to create or update Client or Company
     * @param array $data ['clients' , 'companies']
     * @return array and set $this->client_id  $this->company_id
     */

    protected function createOrUpdateClientOrCompany(array $data) : array{
            if (isset($data["clients"]) & isset($data["clients"]["firstname"])) {
                $this->msg = $this->client->createOrUpdateClient($data["clients"]);
                $this->client_id = $this->msg["id"] ?? null;
            }

            if (isset($data["companies"])) {
                // if client not set - create empty client which firstname as company name
                if(!isset($data["clients"]) & $this->client_id === null){
                    $this->msg = $this->client->createOrUpdateClient([
                        "id" => null,
                        "firstname" => $data["companies"]['name'],
                        "lastname" =>""
                        ]);
                    $this->client_id = $this->msg["id"] ?? null;
                }

                $this->msg = $this->client->createOrUpdateCompany(
                    array_merge($data["companies"], ["client_id" => $this->client_id , "user_id" => $data["user_id"] ?? auth()->user()->local_id ] ));
                $this->company_id = $this->msg["id"] ?? null;
            }
        return  $this->msg;

    }

    /** To create bills for print
     * @param array $data
     * @return mixed
     */

    public function create(array $data)  {
        if($this->check_role("create-full-bill")) {
            $clients = [];
            /**
             * check $data['clients' ,'companies']
             */
            $this->createOrUpdateClientOrCompany($data);
            if ($data["firm_id"] && !isset($data["has_KM"])) {
                $firm =  $this->firm->getById($data["firm_id"]);
                if($firm === null){
                    return ['msg' =>'Oops : that firm not found' , 'code' => 404 , 'type' => 'error'];
                }
                if ( $firm->km > 0) {
                    $data["has_KM"] = true;
                }
            }
            if(isset($data['locale']) && !is_numeric($data['locale'])){
                $data['locale'] = 1;
            }
            $number = $this->creating_request_for_get_number($data);
            if($this->client_id !== null || $this->company_id !== null){
                $clients = ["client_id" => $this->client_id, "company_id" => $this->company_id ];
            }
            $bill = $this->bills->create(array_merge($data, $clients  , $number ));


            foreach ($data["orders"] as $item) {

                    $amount = $item['amount'] ?? 1;
                    $price = ($item['unit_price'] ?? 0) * $amount;
                if(!isset($item['id']) ||  $item['id'] == null  ) {
                    $item['firms_id'] = $data['firm_id'];
                    $order = $this->order->create($item);
                    $bill->orders()->attach($order->id, ['price' => $price, 'amount' => $amount]);
                }else{
                    $bill->orders()->attach( $item['id'], ['price' => $price, 'amount' => $amount]);
                }
            }
        }else return self::PERMISSION_DENIED;

        return $bill;
    }


    /** public method to creating request
     * @param array $data
     * [ 'date' => required
     *  'firm_id' => required ]
     * @return array
     * [ 'number' , 'invoice'
     *
     */

    public function creating_request_for_get_number(array $data) : array{
        $month = Carbon::parse($data['date'] ?? now() )->month;
        $year = Carbon::parse($data['date'] ?? now() )->year;
        $number = $this->getUniqNumber(["firm" => $data['firm_id'], 'month' => $month, 'year' => $year]);// [ 'number' => (int) ]
        $invoice = $this->getInvoiceStr($month, $year, $number["number"]);
        return array_merge($number, ['invoice' => $invoice]);
    }

    /** Function to getting uniq number
     * @param array
     * [ 'number' => required ]
     * @return int[]
     */

    public function getUniqNumber(array  $request)
    {
        $booked = $this->bills->getAllBooking($request)->pluck('number');
        $numbers = $this->bills->getNumbersList($request)->merge($booked)->sort();

        return  $this->getLastNumber($numbers);
    }

    /** Function to init new month
     * @return array
     */

    public function startInitServ():array{
        $r_month = Carbon::now()->addMonth()->month; //add month
        $r_year = Carbon::now()->year; //current year
        $hasBills = $this->bills->countByYearAndMonth($r_month  , $r_year);
        if($hasBills === 0){
            if($r_month <= 1){
                $year = $r_year - 1;
                $prev_m = 12;
            }else{
                $year = $r_year;
                $prev_m = $r_month-1;
            }
            $fixed_bills =  $this->bills->getByYearAndMonth($prev_m , $year  , true );
            if($fixed_bills) {
                foreach ($fixed_bills as $bill) {
                    $new_bill = $bill->replicate();
                    $new_bill->date = Carbon::create($r_year, $r_month, 1, 0, 0, 01);
                    $new_bill->act_user_id = 0;
                    $new_bill->was_paid = null;
                    $new_bill->invoice = $this->getInvoiceStr($r_month , $r_year , $new_bill->number);
                    $new_bill->push();
                    if($bill->orders->count() > 0) {
                        foreach ($bill->orders as $o){
                            $new_o = $o->replicate();
                            $new_o->unit_price = 0;
                            $new_o->bills_id = $new_bill->id;
                            $new_o->push();
                        }
                    }else{
                        $this->order->create( ["name" => $new_bill->name ?? " ",  "bills_id" => $new_bill->id , "amount" => 1 , "currency" => "EUR",
                            "unit_price" => 0 , "unit"=> 1 ] );
                    }
                }
                $bills = $this->bills->getFullBills($r_month, $year);

                $this->msg = array_merge($this->msg, ["msg" => "Initialized",  "bills" => $bills]);
            }else $this->msg = ["msg" => "Fixed bills not found" , "code" => "404" , "type" => "error"];
        }else {
            $this->msg= ["msg" => "That month was been initialized" , "code" =>"200" , "type" => "success" ];
        }
        return $this->msg;
    }

    /** To delete bill by id
     * @param int $id
     * @param bool $without_confirmation
     * @param mixed $value
     * @return array
     */

    public function delete(int $id) : array{
        $has_bill = $this->bills->getSimpleBill($id);
        if ((auth()->id() === $has_bill->act_user_id || $this->check_role("delete-bill"))) {
            try {
                    $has_bill->orders()->detach();
                    $has_bill->delete();
                    $this->msg =['msg' => "Bill deleted" , 'code'=> 200 , 'type'=> 'success'];

            } catch (\Exception $e) {
                $this->msg = ["code" => 404, "msg" => $e->getMessage(), "type" => "error"];
            }
        }else $this->msg = self::PERMISSION_DENIED;
        return $this->msg;
    }

}
