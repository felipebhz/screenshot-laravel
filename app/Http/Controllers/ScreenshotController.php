<?php

namespace App\Http\Controllers;

use App\Models\Screenshot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class ScreenshotController extends Controller
{
    public function index()
    {
    }

    //get ss from 3rd party service
    public function getScreenshot(Request $request, $website)
    {
        // Work in progress
        $websiteScreenshot = DB::table('screenshots')
                ->where('website', '=', $website)
                ->where('updated_at', '<', Carbon::now()->subDays(2))
                ->get();

        $response = Http::post('http://demo4455834.mockable.io/v1/screenshot/' . $website . '');

        $jsonData = $response->json();
        $jsonData['website'] = $website;
    }

    //save ss data on database
    public function store($jsonData)
    {
        Screenshot::create($jsonData);
        return 'OK';
    }

    //save ss file into disk
    public function saveFileDisk($base64String, $fileExtension)
    {
        $fileExtension = getBase64FileExtension($fileExtension);
        $dataToDecode = extractFileEncodedString($base64String);
        $decodedData = base64_decode($dataToDecode);
        $fileName = Str::uuid();
        Storage::disk('local')->put( $fileName . '.' . $fileExtension, $decodedData);
    }
}
