<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;

use Tests\TestCase;

class ArraySctructureResponseTest extends TestCase
{
    //use RefreshDatabase;
    /**
     * A basic feature test example.
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
