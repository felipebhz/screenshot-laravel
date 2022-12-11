<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiEndpointTest extends TestCase
{
    /**
     * A basic test to make sure the endpoint is responding with a 200 HTTP code.
     *
     * @return void
     */
    public function test_api_endpoint_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
