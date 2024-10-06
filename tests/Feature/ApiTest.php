<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{
    use WithFaker;

    public function setUp() :void
    {
        parent::setUp();
        $this->withoutMiddleware();
        // $this->r_date = Carbon::today()->subDays(rand(0, 5))->format("d-m-Y");
      //  $this->withoutExceptionHandling();
    }

    public function testIndex()
    {
        $response = $this->get('/api/admin/api_list');

        $response->assertStatus(200);
    }

    protected function generateRequest(){
        return [
          "name" => $this->faker->company,
          "method" => $this->faker->company,
          "url" => $this->faker->url,
          "desc" => $this->faker->text,
          "example" => $this->faker->text ,
          "user_id" => 1
        ];
    }

/*   этот роут не нужен
    public function testCreateApi(){
        $data = $this->generateRequest();
        $resp = $this->post("/api/admin/api_list/create", $data);
        $resp->assertStatus(200);
    }
*/
    public function testRebuildApi(){
        $resp = $this->get("/api/admin/api_list/reload");
        $resp->assertStatus(200);
    }

}
