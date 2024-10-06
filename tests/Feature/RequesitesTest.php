<?php

namespace Tests\Feature;
use App\Helpers\Facade\Switcher;
use App\Models\Bills\Firms;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RequesitesTest extends TestCase
{

    use WithFaker;
    public $team;
    public $user;

    public function setUp() :void
    {
        parent::setUp();
        $this->user =    User::where('email', 'rowlinest90@gmail.com')->first();
        //TODO: need to mock user
        $this->team  = $this->user->team()->first();

        Sanctum::actingAs(
            $this->user ,
            ['*']
        );
    }

    /**
     * @return mixed
     */
    protected function getAvailablePaymentPlans(){
        return \App\Models\PaymentPlan::where('visible' , true )->get();
    }


    /**
     * thant method used to delete firms from local
     */


    protected function deleteFirmsFromLocalDatabase(){
        Switcher::useLocalDB($this->team->database);
        foreach (Firms::all() as $firm){
            $firm->delete();
        }
    }


    /**
     * method to get requestData
     */

    protected function requestData( array $data = [] ) : array{
        $res_data =  [
            "payment_plan_id" => $data['payment_plan_id'] ?? $this->getAvailablePaymentPlans()->first()->id ,
            "send_pdf" => $data['send_pdf'] ?? false,
        ];
        if(isset($data['send_to'])){
            $res_data =  array_merge(["firm_data" => $data['send_to']] , $res_data );
        }
        return $res_data;
    }

    /**
     * test to set payment plan
     * available status
     * 200 => Updated
     * 404 => Firm data or Personal data need be required
     * 500 => Team not found
     */
/*    public function testSetWhenFirmNotCreated()
    {
        $data = $this->requestData();
        $this->deleteFirmsFromLocalDatabase();
        $response = $this->post('/api/admin/payment_plan' , $data);
        $response->assertStatus(404);
        $response->assertJsonStructure(['code' , 'msg' , 'type']);
    }*/

    /**
     *  test to set paymentPlan with firm_data
     */

    public function testSetWithFirmData(){
        $send_to  = 'company';
        $data = $this->requestData(compact('send_to'));
        $response = $this->post('/api/admin/payment_plan' , $data);
        $response->assertStatus(200);
        $response->assertJsonStructure(['code' , 'msg' , 'type']);
    }

    /**
     * test  to set paymentPlan with personal data
     */
    public function testSetWithPersonalData(){
        $send_to = 'personal';
        $data = $this->requestData(compact('send_to'));
        $response = $this->post('/api/admin/payment_plan' , $data);
        $response->assertStatus(200);
        $response->assertJsonStructure(['code' , 'msg' , 'type']);
    }

    /**
     *  test to set paymentPlan and send bill using Company data
     */

    public function testSetWithCompany(){
        $send_to = 'company';
        $data = $this->requestData(compact('send_to'));
        $response = $this->post('/api/admin/payment_plan' , $data);
        $response->assertStatus(200);
        $response->assertJsonStructure(['code' , 'msg' , 'type']);

    }


    /**
     * test to generate PDF
     */

    public function testGeneratePdf(){



    }

    /**
     *  test to store bill
     *
     */

    public function testToStoreBill(){


    }

    /**
     *  test to local store bill
     *
     */

    public function testToLocalStoreBIll(){


    }




}
