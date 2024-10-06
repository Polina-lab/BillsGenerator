<?php

namespace Tests\Feature;

use App\Models\Users\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersTest extends TestCase
{

    public function setUp() :void
    {
        parent::setUp();
    }

    use WithFaker;


    /** get demo user for test
     * @return array
     */

    public function getUser(){
       $user =  User::where('email' , 'demo@test.ru')->first();
       return ['id' => $user->id , 'email' => $user->email  , 'key' => $user->team->first()->key];
    }

    /** Request with bad password
     *
     */
    public function testLoginFail(){
        $response = $this->post('/api/login' , ["username" => 'rowlinest90@gmail.com' , 'password' => "pkjsjks"]);
        $response->assertStatus(423);
        $response->assertJson(['code' => 423 , "type" => "error"]);
    }

/*    public function testLoginCreateNewDatabase(){
        $response = $this->post('/api/login' , ["username" => 'demo@test.ru' , 'password' => "password"]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token' , 'key' , 'code' , 'token_type']);
        $response->assertJson(['code' => 200]);
    }*/

    public function testRemoteGettingUsers(){
        $resp =  $this->get('/api/v1/users');
        $resp->assertStatus(200);
    }

    protected function getTestDataToRegister( array $data = []): array{
        return  [   "email" => $data["email"] ?? $this->faker->email ,
/*                  "phone" => $data["phone"] ?? $this->faker->phoneNumber,
                    "firstname" => $data["firstname"] ?? $this->faker->firstName ,
                    "lastname" => $data["lastname"] ?? $this->faker->lastName ,
                    'password' => $data["password"] ?? $password,
                    'password2' => $data["password2"] ?? $password,*/
            ];
    }

    /**
     * @param array $data
     * @return  mixed
     */

    protected function requestToRegister(array $data){
       return $this->post("/api/register" , $data);
    }

    /**
     *  Register new User
     */
    public function testRegister(){
        $data = $this->getTestDataToRegister();
        $resp =  $this->requestToRegister($data);
        $resp->assertStatus(200);
        $this->assertDatabaseHas("users" , ["email" => $data["email"] ]);
        return $resp;
    }

    /**
     * Function to get user without Token
     */
    public function testGetUser(){
        $response = $this->get('/api/admin/user'  );
        $response->assertStatus(302);
    }
}
