<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentPlanTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function setUp() :void
    {
        parent::setUp();
        $this->withoutMiddleware();
        //   $this->withoutExceptionHandling();
    }

    public function testGetPaymentPlan(){
        $resp =  $this->get("/api/payment_plans");
        $resp->assertStatus(200);
    }

    public function testStatistic(){
        $resp = $this->get('/api/statistic');
        $resp->assertStatus(200);
    }

}
