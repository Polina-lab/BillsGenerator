<?php

namespace Tests\Feature;

use App\Models\Users\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UsersAdminTest extends TestCase
{

    public $user;
    public $team;

    public function setUp() :void
    {
       // parent::setUp();
       // $this->withoutMiddleware();
        parent::setUp();
        $this->user =  User::where('email', 'rowlinest90@gmail.com')->first();
        $this->team  = $this->user->team()->first();

        //   $this->withoutExceptionHandling();
        Sanctum::actingAs(
            $this->user ,
            ['*']
        );

    }

    use WithFaker;

    public function testGetAllUsers()
    {
        $response = $this->get('/api/admin/users');
        $response->assertStatus(200);
    }


    public function testUpdateNotificationFalse(){
        $response = $this->post('/api/admin/disable_notifications' ,['status' => false]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [ 'email' =>'rowlinest90@gmail.com' , 'disable_suggestions' => 0]);
    }

    public function testUpdateNotificationTrue(){
        $response = $this->post('/api/admin/disable_notifications' ,['status' => true]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [ 'email' =>'rowlinest90@gmail.com' , 'disable_suggestions' => 1]);
    }



    public function testSetDefaultTeam(){
        $data  = ['id'  => 114];
        $response = $this->post('/api/admin/set_default_team' , $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users' , ['email' => 'rowlin@yandex.ru' , 'default_team' => 114]);
    }

    public function testGetUser(){
        $response = $this->get('/api/cf921e3e-0e0f-442f-a40b-8b3277a25b83/user');
        $response->assertStatus(200);
    }



/*    public function testGetAllUsersWithPermission(){
        $resp = $this->get('/api/admin/users_permissions');
        $resp->assertStatus(200);
    }*/
/*
    public function testGetUserById(){
        $response = $this->get('/api/be7c65e6-1a40-4a38-9857-5a3c17c2fd88/user/2'  );
       /// dd($response);
        $response->assertStatus(302);
    }*/

}
