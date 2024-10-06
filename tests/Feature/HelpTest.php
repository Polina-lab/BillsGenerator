<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HelpTest extends TestCase
{

    use WithFaker;

    public function setUp() :void
    {
        parent::setUp();
       // $this->withoutMiddleware();
        // $this->r_date = Carbon::today()->subDays(rand(0, 5))->format("d-m-Y");
        //  $this->withoutExceptionHandling();
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSentFeedback()
    {
        $data =  [
            'email' => "rowlin@yandex.ru",
            'name' => $this->faker->name,
            'text' => $this->faker->text
        ];
        $response = $this->post('/api/feedback' , $data );
        $response->assertStatus(200);
    }
}
