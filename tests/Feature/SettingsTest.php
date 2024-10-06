<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testTimeZoneList()
    {
        $response = $this->get('/api/admin/settings/getTimeZone');
        $response->assertStatus(200);
    }
}
