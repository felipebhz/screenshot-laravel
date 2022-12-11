<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;

use Tests\TestCase;

class ArraySctructureResponseTest extends TestCase
{
    // Uncomment this to refresh the database when running the tests. WARNING: Have a backup or a separate database for this.
    //use RefreshDatabase;

    /**
     * A test to assert the output structure of the data received from the 3rd-party service.
     *
     * @return void
     */
    public function test_array_response_structure()
    {
        $response = Http::post('http://demo4455834.mockable.io/v1/screenshot/bet365');

        $jsonData = $response->json();
        $jsonData['website'] = 'bet365';
        
        $this->assertArrayHasKey('content', $jsonData, "Array doesn't contains 'content' as key");
        $this->assertArrayHasKey('mime-type', $jsonData, "Array doesn't contains 'mime-type' as key");
        $this->assertArrayHasKey('website', $jsonData, "Array doesn't contains 'website' as key");
    }

}
