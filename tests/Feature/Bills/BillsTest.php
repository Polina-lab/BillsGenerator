<?php

namespace Tests\Feature\Bills;

use App\Helpers\Facade\Switcher;
use App\Models\Bills\Bills;
use App\Models\Users\User;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Carbon\Carbon;
class BillsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use FirmTrait;


    protected $team ;
    protected $user ;

    public function setUp() :void
    {
        parent::setUp();
        $this->user =    User::where('email', 'rowlinest90@gmail.com')->first();
        $this->team  = $this->user->team()->first();

        Sanctum::actingAs(
            $this->user ,
            ['*']
        );
    }

    protected function getOrderLast(){



    }


    /**
     * @param array $data
     * @return []|array
     */

    protected function getBillsData( array  $data){
        /**  minimal request
          "firm_id" => "required",
          "deal" => "required",
          "act_user_id" => "required"
          "date" => required,
          "orders" => required,
          "orders.*.name => required,
         */

        $request =  [
          "act_user_id" => $data['act_user_id'] ?? 1,
          "firm_id" => $data['firm_id'] ?? 1,
          "deal" => $data['deal'] ?? rand(0, 5),
          "date" => $data['date'] ?? Carbon::now()->format('Y-m-d'),
          "orders" => [
              [
                  'id' => $data['order_id']  ?? null,
                  "name" => $data['name'] ?? $this->faker->randomElement(['first test bill' , 'second test bill' , 'third test bill'] ),
                  'unit_price' => $data['unit_price'] ??   $this->faker->randomFloat(2, 0 , 9),
                  'amount' => $data['amount'] ?? $this->faker->randomFloat(2, 0 , 9)
              ]
          ]
        ];

        if(isset($data['price']))
            $request['orders'][0] = array_merge($request['orders'][0] , [ 'unit_price' =>  $data['price'] ]);

        if(isset($data['number']))
            $request = array_merge($request , ['number' => $data['number']]);

        if(isset($data['status']))
            $request = array_merge($request , ['status' => $data['status'] ?? rand(0, 1)]);

        if(isset($data['user_id']))
            $request = array_merge($request, ['user_id' => $data['user_id']] );

        if(isset($data['has_KM']))
            $request =  array_merge($request, ['has_KM' => $data['has_KM']] );

        if(isset($data['was_paid']))
            $request =  array_merge($request, ['was_paid' => $data['was_paid']] );


        /*"user_id" => $data['user_id'] ?? 1,
           "orders" => [
               $data['orders'] ?? [ "name" => "test"] ],
           */

        /*"users" => $data['users'] ?? [ "username" => "Test Demo Lastaname"],
          "firms" =>  $data['firms'] ?? [ "name" => "Test Firm" ],*/

        /* "date" => $data['date'] ?? Carbon::now()->format('Y-m-d'),
                 "price" => $data['price'] ?? 0,
                */

        /*"was_paid" => $data['was_paid'] ??  null,
          "has_KM" => $data['has_KM'] ?? false,
          "status" => $data['status'] ?? 0,
         */

        return $request;
    }

    /**
     * @return mixed
     */
    protected function getBillsFromLocalDatabase(){
        Switcher::useLocalDB($this->team->database);
            $bills = Bills::with('orders')->get()->last();
        Switcher::useDefaultDB();
        return $bills;
    }

    public function  testBillGetById(){
        $last_bill = $this->getBillsFromLocalDatabase();
        $responce = $this->get('/api/'. $this->team->key.'/bills/'. $last_bill->id);
        $responce->assertStatus(200);


        $responce->assertJsonStructure([
                "price_km",
                "price_no_km",
                "price_with_km",
                "price",
                "act_user_id",
                "clients",
                "comment",
                "companies",
                "date",
                "deadline",
                "deal",
                "firm_id",
              "firms" =>[
                  //    "id",
                      "name",
                      "company_name",
                      "reg_num",
                      "status",
                      "phone",
                      "address",
                      "representative_custom",
                      "representative_name",
                      /* "email",
                        "footer",
                        "logo",
                        "main_firm",
                        "logo_blob",
                        "view_in_invoice",
                        "is_footer_visible",
                        "representatives",*/
                    "vat" => [[
                              "id",
                              "vat",
                              "default"
                          ]
                        ],

                    "banks" =>[
                        /*[ By default can be not required
                        //"id",
                        "bank_name",
                        "bank_num",
                        "bank_account",
                        "bank_swift",
                        "bank_address",
                        "firm_id"
                        ]*/
                    ],

                ],

                 "vat_id",
                 "id",
                "invoice",
                "locale",
                "name",
                "number",
                "orders"=> [
                      [
                      "bills_id",
                      "name",
                      "amount",
                      "desc",
                      "currency",
                      "unit",
                      "price",
                      "unit_price",
                    ]
                     ],

                  "paid_cash",
                  "penalty",
                  "prepaid",
                  "payment_method",
                  "sender_user"=> [],
                  "currency",
                  "sender_user_id",
                  "status",
                  "updated_at",
                  "user_act" => [
                   // "id",
                    "email",
                    "role",
                    "username"
                  ],
                  "user_id",
                  "users"=> [
                   // "id",
                    "email",
                    "role",
                    "username"
                  ],
                  "was_paid"
        ]);
    }

    public function testBillGetList(){
        $response = $this->get('/api/'. $this->team->key.'/bills' );
        $response->assertStatus(200);
    }

    public function testBillGetIncomming(){
        $response  =  $this->get('/api/'. $this->team->key.'/bills?incoming=true' );
        $response->assertStatus(200);
    }


    public function testBillGetIncommingWithExtraData(){
        $response  =  $this->get('/api/'. $this->team->key.'/bills?incoming=true' );
        $response->assertStatus(200);
    }

    public function testBillCreate()
    {
        $response = $this->post('/api/'. $this->team->key.'/bills/create' , $this->getBillsData([]));
        $response->assertJsonStructure(['msg' , 'code' ,'type']);
        $response->assertStatus(200);
    }

    public function testBillCreateWithPrice(){
        $new_price = $this->faker->randomFloat(2, 0 , 9);
        $new_amount = $this->faker->randomFloat(2, 0 , 9);

        $response = $this->post('/api/'. $this->team->key.'/bills/create' ,
            $this->getBillsData([
                'name' => __METHOD__ ,
                'unit_price' => $new_price,
                'amount' => $new_amount
            ])
        );
        $response->assertJsonStructure(['msg' , 'code' ,'type']);
        $response->assertStatus(200);
      //  $this->assertDatabaseHas('pivot_orders_bills' , ['price' => $new_price , 'amount' => $new_amount]);
    }


    public function testBillCreateWithOrder(){
        $order =  $this->getOrderLast();
        $new_amount = $this->faker->randomFloat(2, 0 , 9);
        $month = rand(1, 12);
        $day = rand(1, 28);
        $date =  Carbon::parse("2021-$month-$day");

            $date = $date->addDay();
            $response = $this->post('/api/' . $this->team->key . '/bills/create',
                $this->getBillsData([
                'date' => $date ,
                "order_id"=> 136,
                "order_name"=> "Ezra Rogahn",
                "order_desc"=> "I shall think nothing of tumbling down stairs! How brave they'll all think me for a minute or two, it was only sobbing,' she thought, and looked at it again=> but he would deny it too: but the Mouse.",
                "unit"=> "Object",
                "unit_price"=> 5.16,
                "order_active"=> 0,
                "order_firms_id"=> 43,
                'amount' => $new_amount,
                "order_categories_id"=> null
            ])
        );
        $response->assertJsonStructure(['msg', 'code', 'type']);
        $response->assertStatus(200);
        $this->assertDatabaseHas('bills' , ['date' => $date ]);
        $this->assertDatabaseHas('pivot_orders_bills' , [ 'amount' => $new_amount , 'orders_id' => 137 ]);
    }


    public function testBillCreateWithStatus(){
        $response  = $this->post('/api/'. $this->team->key.'/bills/create' ,
            $this->getBillsData([
                'name' => __METHOD__ ,
                'price' => $this->faker->randomFloat(2, 0 , 9),
                'status' => rand(0, 1),
            ])
        );
        $response->assertJsonStructure(['msg' , 'code' , 'type']);
        $response->assertStatus(200);
    }

    /**
    "id" => "required",
    "number" =>"required",
    "firm_id" => "required",
    "user_id" => "integer|nullable",
    "price" => "required",
    'bank_id' => 'nullable',
    "status" => "required",
    "date" => "required",
    "act_user_id" => "integer|nullable",
     */

    protected function updateBill( $data = []){
        $response  = $this->post('/api/'. $this->team->key.'/bills/update/'. $data['bill_id'] ,
            $this->getBillsData([
                'number' => $data['bill_number'],
                'name' =>  $data['bill_name'],
                'user_id' => $data['bill_user_id'],
                'firm_id' =>  $data['firm_id'],
                'price' => $data['price'] ?? $this->faker->randomFloat(2, 0 , 9),
                'status' => $data['status'] ?? rand(0, 1),
                //'vat_id' => $data['vat_id'] ?? null,
                'was_paid' => $data['was_paid'] ?? boolval(rand(0, 1)),
                'order_id' => $data["order_id"] ,
            ])
        );
        $response->assertStatus(200);
    }

    public function testBillUpdate(){
        $bill =  $this->getBillsFromLocalDatabase();
        $order = $bill->orders->last();
        $new_name = __METHOD__ ." changed" . rand(0, 1000) ;
        $this->updateBill([
            'bill_id' => $bill->id,
            'bill_number' => $bill->number,
            'bill_name' => $new_name,
            'bill_user_id' => $bill->user_id,
            'firm_id' => $bill->firm_id,
            'order_id' => $order->id,
            ]);

        $this->assertDatabaseHas('orders' , ['name' => $new_name] );
    }

    public function testBillUpdateWhenChangedFirmId(){
        $bill =  $this->getBillsFromLocalDatabase();
        $firms = $this->getFirmsFromLocalDatabase($this->team->database);
        $order = $bill->orders->last();
        $firm_number = $firms->where('id' , '!=' , $bill->firm_id)->last()->id;
        $new_name = __METHOD__ ." changed" . rand(0, 1000) ;
        $this->updateBill([
            'bill_id' => $bill->id,
            'bill_number' => $bill->number,
            'bill_name' => $new_name,
            'bill_user_id' => $bill->user_id,
            'firm_id' => $firm_number,
            'order_id' => $order->id,
        ]);
        $this->assertDatabaseMissing('bills' , ['id' => $bill->id ,'invoice' => $bill->invoice ] );
    }


    public function testBillDelete(){
        $bill =  $this->getBillsFromLocalDatabase();
        $response = $this->delete('/api/' . $this->team->key . '/bills/' . $bill->id);
        $response->assertStatus(200);
    }

/*    public function creatingBills( array $data){
        $response = $this->post('/api/' . $this->team->key . '/bills_gen/create' ,
        [

        ]
        );
        $response->assertStatus(200);
    }*/

/*    public function testCreatingBills(){
        $bill =  $this->getBillsFromLocalDatabase();
    }*/

/*
    public function testUpdatingBills(){
    }
*/



}
