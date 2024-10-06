<?php

namespace App\Services;

use App\Helpers\Facade\Switcher;
use App\Models\Bills\Bills;
use App\Models\Bills\Orders;
use App\Models\Users\Team;
use App\Repositories\Bills\BillsRep;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;
use App\Traits\GenerateBillNumberTrait;

class SystemBills
{
    public $cur_order ;
    public $amount ;
    public $cur_client;
    public $locale;
    protected $team;
    protected $price;

    use GenerateBillNumberTrait;
    protected $firm_data = [
        'name' =>  'Glo Real Network OÜ',
        'company_name' => 'Glo Real Network OÜ',
        'reg_num' =>  '14118122',
        'phone' => '+372 555 99 313',
        'address' => 'Estonia pst 5, kab. 304',
        'banks'=>[
            'bank_name'=> 'LHV',
            'bank_num'=> 'EE187700771002405343',
            'bank_account' => '',
            'bank_swift' => 'LHVBEE22',
            'bank_address' => 'Tartu mnt 2 Tallinn, 10145, Estonia',
        ],
        'vat' =>[
            'vat' => 20
        ]
    ];

    public function __construct(
        Team $team,
        array $order,
        array $client,
        string $locale
    ){
        $this->team = $team;
        $this->cur_order = $order;
        $this->cur_client = $client;
        $this->amount =  1; // as a month
        $this->locale = $locale ?? 'en';
       // $this->price  =  $order[0]['unit_price'] * $this->amount;
    }


    /** format price
     * @return array
     */

    protected function getPrice(){
        $price_km = floatval($this->price) * floatval($this->firm_data['vat']['vat']) / 100;
        $data['km_percent'] = floatval($this->firm_data['vat']['vat']);
        $data["price_km"] =  number_format( $price_km ,2 , '.', '' ) ;//
        $data["price_no_km"] = number_format($this->price ,2 ,'.',  '');
        $data["price_with_km"] = number_format( floatval($this->price) + floatval($price_km)  ,2 , '.', '');//,',',  ''
        return $data;
    }


    /**
     *
     */

    protected function getPriceIfTotalWithVat(){
        $price_km = floatval($this->price) * floatval($this->firm_data['vat']['vat']) / 100;
        $data['km_percent'] = floatval($this->firm_data['vat']['vat']);
        $data["price_km"] =  number_format($price_km ,2 ,'.',  '');
        $data["price_no_km"] = number_format( floatval($this->price) - floatval($price_km)  ,2 , '.', '');//,',',  ''
        $data["price_with_km"] = number_format( floatval($this->price) ,2 , '.', '' ) ;
        return $data;
    }


    /** here need be user main_firm
     * @return array
     */

    protected function getNumber(){
        $bills = new BillsRep(new Bills());
        $month = Carbon::parse($data['date'] ?? now() )->month;
        $year = Carbon::parse($data['date'] ?? now() )->year;
        $booked = $bills->getAllBooking(['firm_id' => 0 , 'month' => $month, 'year' => $year , 'incoming' => true])->pluck('number');
        $numbers = $bills->getNumbersList(['firm_id' => 0 , 'month' => $month, 'year' => $year , 'incoming' => true])->merge($booked)->sort();
        $number =  $this->getLastNumber($numbers);
        $invoice = $this->getInvoiceStr($month, $year, $number["number"]);
        return array_merge($number, ['invoice' => $invoice]);
    }

    public function printData(){
        return  $this->billing();
    }

    /** return Response with file
     * @return mixed
     */

    public function generatePDF(){
        $data  = $this->billing();
        $pdf = PDF::loadView("bills.index2" , compact('data') );
        return $pdf->download('invoice.pdf');
    }

    /** return html (string)
     * @return string
     */

    public function printBills(){
        $data = $this->billing();
        return view("bills.index2" , compact('data'))->render();
    }

    public function log(){
        info(json_encode($this->billing()));
    }


    public function billing(){
        if(sizeof($this->cur_order) ===  1){
            $this->price = $this->cur_order[0]['unit_price'] * $this->amount;
            $orders  = [array_merge( $this->cur_order[0] , ['amount' => $this->amount , 'price' => $this->price])];
        }else{
            dd($this->cur_order);
        }
        $bill_data = [
            'locale' => $this->locale,//TODO: need to get user default locale
            'firms' => $this->firm_data,
            'orders' =>  $orders,
            'currency' => 'EUR', //by default
            'date' => Carbon::now()->format('Y-m-d'),
            'deadline' => Carbon::now()->addDays(3)->format('Y-m-d'),
            'payment_method' => 3,
            'payment_method_name' => 'transfer',
            'sender_user' => [ 'username' => 'Glo Real Network OÜ' ],
            'clients' => $this->cur_client,
        ];

        return  array_merge($bill_data ,  $this->getPriceIfTotalWithVat() ,  $this->getNumber());
    }


    public function store(){
        $bills = new BillsRep(new Bills());
        if($this->locale === 'ee') $this->locale = 1;
        else if($this->locale === 'ru') $this->locale = 2;
        else $this->locale = 3;

        $data = $this->billing();

        if(isset($data['clients']['company_name'])  || isset($data['clients'][0]['company_name'])){
            $data['firm_id'] = $data['clients']['id'] ?? $data['clients'][0]['id'];
        }else{
            $data['client_id'] = $data['clients']['id'] ?? $data['clients'][0]['id'];
        }

        $buyer  = $this->team->user->where('has_buyer' , true)->first();
        $data['act_user_id'] = $buyer->local_id; // for default
        $data['user_id'] = $buyer->local_id; //

        if($data['payment_method_name'] ===  'transfer')
            $data['payment_method'] = 3;
        else if($data['payment_method_name'] ===  'card')
            $data['payment_method'] =  2;
        else if($data['payment_method_name'] ===  'cash')
            $data['payment_method'] = 3;

        $bill = $bills->create(array_merge( $data , ['type' => false  ]  ));
        foreach ($this->cur_order as $o) {
            $bill->orders()->attach([ $o['id'] => ['price' => $this->price, 'amount' => $this->amount]]);
       }
        return $bill;
    }


}
