<?php

namespace App\Http\Controllers;

use App\Models\Screenshot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ScreenshotController extends Controller
{
    public function index()
    {
    }

    public function getScreenshot(Request $request, $website)
    {
        $response = Http::post('http://demo4455834.mockable.io/v1/screenshot/' . $website . '');

        $jsonData = $response->json();
        $jsonData['website'] = $website;
        $this->store($jsonData);
    }

    public function store($jsonData)
    {
        Screenshot::create($jsonData);
        return 'OK';
    }
}
