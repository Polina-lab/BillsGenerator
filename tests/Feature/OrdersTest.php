<?php


namespace Tests\Feature;


use App\Helpers\Facade\Switcher;
use App\Models\Bills\Categories;
use App\Models\Bills\Firms;
use App\Models\Bills\Orders;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrdersTest extends TestCase
{

    use WithFaker;
    public $team;
    public $user;

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

    /*public function testGetOrderById()
    {
        Switcher::useLocalDB($this->team->database);
        $res = Orders::where('id', '118')->with('categories')->first();
        $res->assertStatus(200);

        // $res = $this->get('/api/'. $this->team->key.'/orders/118');
    }*/


    /**
     *  Standart Request
     */

    public function testGetOrderStat(){
        $order =  $this->getLastOrderWithBills();
        $req = $this->get( '/api/'. $this->team->key.'/order_stat/'. $order->id);
        $req->assertStatus(200);
        $req->assertJsonStructure(['code' ,'data']);
    }

/*
    public function testGetOrderStatWithPeriod(){
        $date_start = Carbon::parse("2020-" . rand(1, 6)."-". rand(1,30) );
        $date_end = Carbon::parse("2020-" . rand(6, 12)."-". rand(1,30) );
        $period =  'month';
        $order =  $this->getLastOrder();
        $req = $this->get('/api/'. $this->team->key.'/order_stat/'. $order->id.
            '/?start='.$date_start .
            '&end='. $date_end .
            '&month=' . $period);
        $req->assertStatus(200);
    }*/

    public function testGetOrderStatIfNotBills(){
        $order =  $this->getLastOrderWithNoBills();
        $req = $this->get( '/api/'. $this->team->key.'/order_stat/'. $order->id);
        $req->assertStatus(204);
    }


    public function getOrderGetBy($data){
        $req = '/api/'. $this->team->key.'/orders';
        $req .= isset($data['firm_id']) ? "?firms_id=" .$data['firm_id'] : '';
        $req .= isset($data['category_id']) ? "?category_id=". $data['category_id']: '';
        $req .= isset($data['orderBy']) ? '&orderBy=' . $data['orderBy'] : '';
        $req .= isset($data['orderType']) ? '&orderType=' . $data['orderType'] : '';
        return $this->get(  $req);

    }

    public function testOrdersGetByFirm(){
       $firm = $this->getLastFirm();
       $res =   $this->getOrderGetBy(['firm_id' => $firm->id]);
       $res->assertStatus(200);
    }

    public function testOrdersGetByCategory(){
        $category = $this->getLastCategory();
        $res =   $this->getOrderGetBy(['category_id' => $category->id]);
        $res->assertStatus(200);
    }

    public function testOrdersGetByOrder(){
        $category = $this->getLastCategory();
        $res =   $this->getOrderGetBy([ 'category_id' => $category->id  , 'orderBy' => 'name' , 'orderType' => "ASC"]);
        $res->assertStatus(200);
    }


    public function testOrdersGet(){
        $response = $this->get('/api/'. $this->team->key.'/orders');
        $response->assertStatus(200);
        $response->assertJsonStructure(['current_page' , 'data' , 'total' , 'per_page']);
    }

    protected  function getLastOrder(){
        Switcher::useLocalDB($this->team->database);
        $order =  Orders::all()->last();
        Switcher::useDefaultDB();
        return $order;
    }


    protected  function getLastOrderWithNoBills(){
        Switcher::useLocalDB($this->team->database);
        $orders =  Orders::with('bills')->get();
        foreach ($orders as $order){
            if($order->bills->count() == 0){
                Switcher::useDefaultDB();
                return $order;
            }
        }
    }


    protected  function getLastOrderWithBills(){
        Switcher::useLocalDB($this->team->database);
        $orders =  Orders::with('bills')->get();
        foreach ($orders as $order){
            if($order->bills->count() > 1){
                Switcher::useDefaultDB();
                return $order;
            }
        }
    }

    protected function getLastFirm(){
        Switcher::useLocalDB($this->team->database);
        $firm =  Firms::all()->last();
        Switcher::useDefaultDB();
        return $firm;
    }

    protected function getLastCategory(){
        Switcher::useLocalDB($this->team->database);
        $categories =  Categories::all()->last();
        Switcher::useDefaultDB();
        return $categories;
    }


    protected function getNewOrder(array  $data){
        $firm = $this->getLastFirm();
        return [
            'name' => $data['name'] ??  $this->faker->name,
            'desc' => $data['desc'] ?? $this->faker->realText($maxNbChars = 200, $indexSize = 2),
            'unit' => $data['unit'] ??  "Object", // this is string
            'unit_price' => $data['unit_price'] ?? $this->faker->randomFloat(2,0,9),
            'categories_id' => $data['categories_id'] ?? null,
            'firms_id' => $data['firms_id'] ?? $firm->id
        ];
    }

    public function testOrderCreate(){
        $name = $this->faker->name;
        $response =  $this->post('/api/' . $this->team->key . '/order/create' , $this->getNewOrder([ 'name' => $name ]) );
        $response->assertStatus(200);
        $response->assertJsonStructure(['code' , 'msg' , 'type']);
        $this->assertDatabaseHas('orders' , ['name' =>  $name ]);
    }

    public function testOrderCreateFail(){
        $response =  $this->post('/api/' . $this->team->key . '/order/create' , $this->getNewOrder(['name' => '' , 'unit_price' => 'test']) );
        $response->assertStatus(302);
    }

    public function testOrderUpdate(){
        $order =  $this->getLastOrder();
        $new_value = "changed_" . Str::random(16);
        $firm = $this->getLastFirm();
        $data =$this->getNewOrder(['name' => $new_value , 'firms_id' => $firm->id ]);
        $response =  $this->patch('/api/' . $this->team->key . '/order/'. $order->id , $data  );
        $response->assertStatus(200);
        $response->assertJsonStructure(['code' , 'msg' , 'type']);
        //Switcher::useLocalDB($this->team->database);
        $this->assertDatabaseHas('orders' , ['name' =>  $new_value]);
    }


    public function testOrderDelete(){
        $order =  $this->getLastOrder();
        $response =  $this->delete('/api/' . $this->team->key . '/order/'. $order->id );
        $response->assertStatus(200);
        $response->assertJsonStructure(['code' , 'msg' , 'type']);
        Switcher::useLocalDB($this->team->database);
        $this->assertDatabaseMissing('orders' , ['id' =>  $order->id]);
    }

/*    public function testSearchByName(){
        Switcher::useLocalDB($this->team->database);
        $order = $this->getLastOrder();
        $name = $order->name;
        $response =  $this->post('/api/' . $this->team->key . '/orders_search/', ['name' => $name ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' , 'msg' , 'code' , 'type'] );
    }
*/


}
