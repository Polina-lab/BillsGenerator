<?php

namespace Tests\Unit;

use App\Models\Bills\Bills;
use App\Repositories\Bills\BillsRep;
use App\Repositories\Bills\FirmRep;
use App\Repositories\Bills\OrdersRep;
use App\Services\Bills\BillsService;
use App\Services\Bills\ClientService;
use App\Traits\BillsTrait;
use Tests\TestCase;
use App\Models\Bills\FirmsVat;


class BillsTest extends TestCase
{

    private $bills_real;
    public $bills;
    private $client ;
    private $order;
    private $firm;
    public $vat;


    protected function setUp(): void
    {
        $this->bills_real = new BillsRep(new Bills());
        $this->client = $this->createMock(ClientService::class);
        $this->bills = $this->createMock(BillsRep::class);
        $this->order = $this->createMock(OrdersRep::class);
        $this->firm = $this->createMock(FirmRep::class);
        $this->vat = $this->createMock(FirmsVat::class);
    }

    protected function getBill(){
        return [
              "id" => null,
              "number" => 4,
              "invoice" => 4,
              "user_id" => 1,
              "orders" => [[
                  "name" => "1111"
                ]],
              "act_user_id" => 1,
              "users" => [
                "username" => "DemoUser DemoUser"
              ],
              "firms" =>  [
                "name" => "test222"
              ],
              "firm_id" => 2,
              "date" => "19.08.2021",
              "price" => 0,
              "deal" => 4,
              "was_paid" => null,
              "has_KM" => false,
              "status" => 0,
              "updated" => 1,
            ];
    }


/*    public function testCheckVat(){
            $obj = $this->getObjectForTrait(BillsTrait::class);
            $price = rand(100 , 10000);
            $firm_vat = rand(1, 99);
            $res =  $obj->check_Km( $firm_vat, $price   );
            $price_k = floatval($price) * floatval($firm_vat) /100;
            $this->assertTrue( floatval($res) - floatval($price_k) ==  floatval($price ) );
    }*/

    public function testCheckVatIfVatNUll(){
        $obj = $this->getObjectForTrait(BillsTrait::class);
        $price = rand(100 , 10000);
        $firm_vat = null;
        $res =  $obj->check_Km( $firm_vat, $price   );
        $this->assertTrue( floatval($res) ===  floatval($price) );
    }





    public function testCreateBills()
    {
        // @note we do not expect function track to be called
        $res = new BillsService($this->bills_real  , $this->client , $this->firm, $this->order);
       // $res->createBill($this->getBill());
        //TODO:


        //$this->billsService->expects($this->never());
        //dd($this->billsService);
        $this->assertTrue(true);
    }

    public function testGetAllBills(){
        $this->assertTrue(true);
    }

}
