<?php

namespace Tests\Feature;

use App\Models\Coupons;
use App\Repositories\CouponsRep;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Tests\TestCase;

class CouponsTest extends TestCase
{


    use WithFaker;

    public function setUp() :void
    {
        parent::setUp();
        $this->withoutMiddleware();
        // $this->r_date = Carbon::today()->subDays(rand(0, 5))->format("d-m-Y");
        //  $this->withoutExceptionHandling();
    }


    /** Method to generate testData
     * @param array| null
     * @return array
     */
    protected function getTestData($data = null) : array{
        return [
                'code' => $data["code"] ?? Str::random(6),
                'amount_percent' => $data["amount_percent"] ?? null,
                'amount_eur' =>  $data['amount_eur'] ?? rand(1, 9) - 0. . '.' . rand(1, 9) ,
                'start_date' => $data['start_date'] ?? Carbon::now(),
                'end_date' => $data['end_date'] ?? Carbon::now()->addDays(7),
                'usages_remaining' => $data['usages_remaining'] ?? rand(1, 3),
                'user_id' => $data['user_id'] ?? 1
            ];
    }

    /**
     *  Test to get Coupons
     */
    public function testIndex(){
        $resp =  $this->get("/api/admin/coupons")->assertStatus(200);
        $resp->assertJsonStructure([ "data" => [], "total"  ]);
    }

    /**
     * Test to getting Coupons
     */

    public function testGetCouponByKey(){
        $coupon = Coupons::first();
        $resp = $this->get('/api/admin/coupon/' . $coupon->code );
        $resp->assertJsonStructure(['msg'  , 'code']);
    }

    /**
     *  Test to create Coupons
     */

    public function testCreateCoupon()
    {
        $testData = $this->getTestData();
        $resp =  $this->post("/api/admin/coupons/create" , $testData)->assertStatus(200);
        $resp->assertJsonStructure([ "code", "msg" , 'type']);

    }

    /**
     *  Test to update Coupons
     */

    public function testUpdateCoupon(){
        $id = Coupons::orderBy("created_at" , "DESC")->value("id");
        $testData  = $this->getTestData();
        $resp = $this->patch('/api/admin/coupons/'. $id , array_merge( $testData , ['id' => $id] ))->assertStatus(200);
        $resp->assertJsonStructure([ "code", "msg" , 'type']);
        $this->assertDatabaseHas('coupons', ['code' => $testData["code"]]);
    }

    public function testDeleteCoupon(){
        $id = Coupons::orderBy("created_at" , "DESC")->value("id");
        $resp = $this->delete('/api/admin/coupons/'. $id )->assertStatus(200);
        $resp->assertJsonStructure([ "code", "msg" , 'type']);
        $this->assertDatabaseMissing('coupons', ['id' => $id]);
    }

}
