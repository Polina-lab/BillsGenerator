<?php

namespace Tests\Feature\Bills;

use App\Models\Users\User;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FirmsTest extends TestCase
{
    use FirmTrait;

    protected $team ;
    protected $user ;

    public function setUp() :void
    {
        parent::setUp();
        $this->user =  User::where('email', 'rowlinest90@gmail.com')->first();
        $this->team  = $this->user->team()->first();

        Sanctum::actingAs(
            $this->user ,
            ['*']
        );
    }

    /**
     * by default returned only active
     */
    public function testFirmGet(){
        $res = $this->get('/api/' . $this->team->key . '/firms/');
        $res->assertStatus(200);
        $res->assertJsonStructure(
            [
                [
                    'id',
                    'name',
                    'company_name',
                    'status',
                    'reg_num',
                    'phone',
                    'address',
                    'vat_reg_num',
                    'representatives',
                   'representatives_list' => [
                    ],
                    'representative_custom',
                    'representative_name',
                    'footer',
                    "requisites_visible",
                    "is_footer_visible" ,
                    "logo",
                    "email",
                    "view_in_invoice",
                    "main_firm" ,
                    "banks" => [
                        /*[ // banks can be not required by default
                            "bank_name",
                            "bank_num",
                            "bank_account",
                            "bank_swift",
                            "bank_address"
                        ]*/
                    ],
                    "vat" => [
                        [
                            "vat",
                            "default"
                        ]
                    ],
                    "vat_default" => [
                        "vat"
                    ]
                ]
            ],
        );

    }

    /**
     * if have param all - return all
     */

    public function testFirmGetAll(){
        $res = $this->get('/api/' . $this->team->key . '/firms?all=true');
        $res->assertStatus(200);
    }


    /**
     *  method to create
     */

    public function testFirmCreate()
    {
        $name  = $this->faker->company;
        $response = $this->post('/api/' .$this->team->key.'/firm/create' , $this->getNewFirmData(['name' => $name]) );
        $response->assertStatus(200);
        $response->assertJsonStructure(['code' , 'msg' , 'type' , 'firm']);
        $firms = $this->checkFirmInDatabase($this->team->database ,  [ 'name' , '=' , $name]);
        $this->assertNotNull($firms);
    }

    /**
     * @param array $data
     * method to create with VAT
     */

    public function testFirmCreateWithVat(array $data = []){
        $name  = $this->faker->company;
        $vat_data= $data['vat'] ?? rand(1 , 99) ;
        $vat = $this->getNewVat(['vat'=> $vat_data ,
            'default' => $data['default'] ?? false ]);
        $response = $this->post('/api/' .$this->team->key.'/firm/create' ,
            $this->getNewFirmData(['name' => $name , 'vat' => $vat]));
        $response->assertStatus(200);
        $response->assertJsonStructure(['code' , 'msg' , 'type' , 'firm']);
        $firms_vat = $this->checkVatInDatabase($this->team->database ,  [[ 'vat' , '=' , $vat_data]]);
        $firms = $this->checkFirmInDatabase($this->team->database ,  [ 'name' , '=' , $name]);
        $this->assertNotFalse(sizeof($firms) > 0);
        $this->assertNotFalse(sizeof($firms_vat) > 0);
    }

    /**
     *  method to create with Banks
     */

    public function testFirmCreateWithBanks(){
        $name = $this->faker->company;
        $bank_name =  $this->faker->company;
        $bank = $this->getNewBank([
            "bank_name" => $bank_name ,
            "bank_swift" => $data['bank_swift'] ?? $this->faker->swiftBicNumber,
            "bank_address" => $data['bank_address'] ?? $this->faker->address,
            "bank_num" => $data['bank_num'] ?? $this->faker->bankAccountNumber,
        ]);
        $response = $this->post('/api/' .$this->team->key.'/firm/create' ,
            $this->getNewFirmData(['name' => $name , 'bank' => $bank] ) );
        $response->assertStatus(200);
        $firms = $this->checkFirmInDatabase($this->team->database ,  [ 'name' , '=' , $name]);
        $this->assertNotFalse(sizeof($firms) > 0);
        $banks = $this->checkBankDetailsInDatabase($this->team->database ,  [['bank_name' , '=' , $bank_name]]);
        $this->assertNotFalse(sizeof($banks) > 0);
    }

    public function  testFirmCreateWithImage(){
        $response = $this->post('/api/' .$this->team->key.'/firm/create' ,
            $this->getNewFirmData(["logo" => "
            data:image/jpeg;base64,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
            "] ) );
        $response->assertStatus(200);
    }

    /**
     * method to update
     */

    protected function firmUpdate(array $firm , array $changes  ){
        $result = $this->post('/api/' . $this->team->key . '/firm/update/'. $firm['id'] , $changes);
        $result->assertStatus(200);
        $result->assertJsonStructure(['code' , 'msg' , 'type' ]);
    }

    public function testFirmUpdateName(){
        $firm =  $this->getLastFirmWith($this->team->database , [['status' ,'=' , true]])->toArray();
        $new_name = $this->faker->company;
        $changes =  $firm ;
        $changes['name'] = $new_name;
        $this->firmUpdate($firm ,$changes);
        $firms = $this->checkFirmInDatabase($this->team->database ,  [ 'name' , '=' , $new_name]);
        $this->assertNotFalse(sizeof($firms) > 0);
    }

/*can be broked if firm not created  TODO: need to mock object*/
    /*
    public function testFirmUpdateVat(){
        $firm =  $this->getLastFirmWith($this->team->database , [['status', '=' , true]] ,
            [['vat' , '>' , '5'],['default' , '=' , 1]])->toArray();
        $new_value =  rand(80,99);
        $changes =  $firm;
        $changes['vat'][0]['vat'] = $new_value;
        $this->firmUpdate($firm , $changes);
        $vat = $this->checkVatInDatabase($this->team->database, [['id', '=', $firm['vat'][0]['id']], ['vat' , '=', $new_value]]);
        $this->assertNotFalse(sizeof($vat) > 0);
    }*/
/*
 * TODO://but need to work !!
    public function testFirmUpdateBank(){
        $firm =  $this->getLastFirmWith($this->team->database , [['status', '=' , true]] ,
            [['bank_name' , '>' , '5'],['default' , '=' , 1]])->toArray();
        $new_value =  rand(80,99);
        $changes =  $firm;
        $changes['vat'][0]['vat'] = $new_value;
        $this->firmUpdate($firm , $changes);
        $vat = $this->checkVatInDatabase($this->team->database, [['id', '=', $firm['vat'][0]['id']], ['vat' , '=', $new_value]]);
        $this->assertNotFalse(sizeof($vat) > 0);
    }

    public function  testFirmUpdateMain_firm(){



    }
*/

    /**
     * method to delete
     * мы не можем просто взять и удалить фирму !!
     *
     */

    public function testFirmDelete(){
        $firm =  $this->getLastFirm($this->team->database);
        if($firm->status === 1) {
            $result = $this->delete('/api/' . $this->team->key . '/firm/delete/' . $firm->id);
            $result->assertStatus(206);
            $result->assertJsonStructure(['code', 'msg', 'type']);
            $firm = $this->checkFirmInDatabase($this->team->database, ['id', '=', $firm->id]);
            $this->assertTrue(sizeof($firm) === 1);
            $this->assertTrue($firm->first()->status === 0);
        }else {
            $vat_id = $firm->vat->first() ? $firm->vat->first()->id : null;
            $bank_id = $firm->banks->first() ? $firm->banks->first()->id : null;
            $result = $this->delete('/api/' . $this->team->key . '/firm/delete/' . $firm->id);
            $result->assertStatus(200);
            $result->assertJsonStructure(['code', 'msg', 'type']);
            $firm = $this->checkFirmInDatabase($this->team->database, ['id', '=', $firm->id]);
            $this->assertNotFalse(sizeof($firm) === 0);
            if ($bank_id) {
                $bank = $this->checkBankDetailsInDatabase($this->team->database, [['id', '=', $bank_id]]);
                $this->assertNotFalse(sizeof($bank) === 0);
            }
            if ($vat_id) {
                $vat = $this->checkVatInDatabase($this->team->database, [['id', '=', $vat_id]]);
                $this->assertNotFalse(sizeof($vat) === 0);
            }
        }
    }

    /**
     * по второму запросу удаляем с базы
     */

    public function testFirmDeleteFromDB(){
        $this->testFirmDelete();
    }

}
