<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{
    /**
     * Test API root endpoint.
     */
    public function test_api_root_endpoint(): void
    {
        $response = $this->getJson('/api/v1');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Welcome to Comments API',
                'version' => '1.0.0',
                'status' => 'ok'
            ]);
    }
}




