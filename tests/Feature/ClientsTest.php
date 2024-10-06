<?php

namespace Tests\Feature;

use App\Helpers\Facade\Switcher;
use App\Models\Clients\Clients;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ClientsTest extends TestCase
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

    /**
     * @param null|int $id
     * @param array $data [['name', '=' , value ]]
     * @return mixed
     */

    protected function getClientFromBD( $id = null , $data =[]){
        Switcher::useLocalDB($this->team->database);
        if($id){
            $res = Clients::where('id' , $id)->first();
        }elseif (!empty($data)){
            $res = Clients::where($data)->first();

        }else{
            $res = Clients::all()->first();
        }
        Switcher::useDefaultDB();
        return $res;
    }


    protected function getClientData( array $data = [] ) : array{
        return [
        "firstname" => $data['firstname'] ?? $this->faker->firstName,
        "lastname" => $data['lastname'] ?? $this->faker->lastName,
        "type" => $data['type'] ?? rand(1, 2),
        "address" => $data['address'] ?? $this->faker->address,
        "id_code" => $data['id_code'] ?? Str::random(13),
        "comment" => $data['comment'] ?? $this->faker->text(200),
        "link" => $data['link'] ?? $this->faker->url,
        "status" => $data['status'] ?? rand(0, 1),
        "lepping" => $data['lepping'] ?? rand(0,1),
        "agent" => $data['agent'] ?? rand(0,1),
        "work_status" => $data["work_status"] ?? rand(1, 5),
        "phone" => $data['phone'] ?? $this->faker->phoneNumber,
        "reg_num" => $data['reg_num'] ?? Str::random(13), // ?
        "email" => $data['email'] ?? $this->faker->companyEmail ,
        "show_in_bills" => rand(0,1),
        ];
    }

    public function testClientGetAll(){
        $resp = $this->get('/api/' . $this->team->key . '/clients/getAll' );
        $resp->assertStatus(200);
        foreach ($resp->getData() as $cl ){
            $this->assertEquals(1,  $cl->status);
        }
    }

    public function testClientGetAllInActive(){
        $resp = $this->get('/api/' . $this->team->key . '/clients/getAll?active=true'  );
        $resp->assertStatus(200);
        foreach ($resp->getData() as $cl ){
            $this->assertEquals(0,  $cl->status);
        }
    }

    public function testClientById(){
        $client =  $this->getClientFromBD();
        $resp = $this->get('/api/' . $this->team->key . '/client/'. $client->id );
        $resp->assertStatus(200);
    }

    public function testGetClients(){
        $resp = $this->get('/api/' . $this->team->key . '/clients/getAll' );
        $resp->assertStatus(200);
    }

    public function testClientCreate(){
        $firstname  =  $this->faker->firstName;
        $resp = $this->post("/api/{$this->team->key}/client/create" ,['client' => $this->getClientData(['firstname' => $firstname])]);
        $resp->assertStatus(200);
        $resp->assertJsonStructure(['msg' , 'code' , 'type']);
        $client =  $this->getClientFromBD(null , [['firstname' , '=' , $firstname]] );
        $this->assertNotNull($client);
    }

    public function testClientUpdate(){
        $firstname  =  $this->faker->firstName;
        $lastname = $this->faker->lastName;
        $client = $this->getClientFromBD();
        $resp = $this->post("/api/{$this->team->key}/client/update/{$client->id}" ,['client' =>
            $this->getClientData(['firstname' => $firstname , 'lastname' => $lastname] )]);
        $resp->assertStatus(200);
        $resp->assertJsonStructure(['msg' , 'code' , 'type']);
        $client =  $this->getClientFromBD(null , [['firstname' , '=' , $firstname]] );
        $this->assertNotNull($client);
    }

    public function testClientDelete(){
        $client = $this->getClientFromBD();
        $resp = $this->delete("/api/{$this->team->key}/client/delete/{$client->id}");
        $resp->assertStatus(200);
        $resp->assertJsonStructure(['msg' , 'code' , 'type']);
        $client =  $this->getClientFromBD($client->id);
        $this->assertNull($client);
    }
}
