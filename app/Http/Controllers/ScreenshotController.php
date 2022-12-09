<?php

namespace App\Http\Controllers;

use App\Models\Screenshot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;


class ScreenshotController extends Controller
{
    public function index()
    {
        foreach (Screenshot::all() as $image) {
            echo $image->website;
        }
    }

    public function checkImageNeedsUpdate($website)
    {
        return Screenshot::select('id', 'website')->where('website', '=', $website)->where('updated_at', '<', Carbon::now()->subDays(2))->get()->count() > 0;
    }

    public function checkImageExists($website)
    {
        $recordsFound = DB::table('screenshots')->select('filename')->where('website', '=', $website)->count();
        $exists = ($recordsFound > 0) ? true : false;
        return $exists;
    }

    public function getCurrentWebsiteFilename($website)
    {
        return Screenshot::where('website', $website)->first()->filename;
    }

    //get ss from 3rd party service
    public function updateScreenshot(Request $request, $website)
    {
        $response = Http::post('http://demo4455834.mockable.io/v1/screenshot/' . $website . '');

        $jsonData = $response->json();
        $jsonData['website'] = $website;
        
        if ($this->checkImageExists($website) && $this->checkImageNeedsUpdate($website)) {
            $oldFileName = $this->getCurrentWebsiteFilename($website);
            $currentSavedFileName = $this->saveFileDisk($jsonData['content'], $jsonData['mime-type'], $website);
            if (Storage::disk('local')->exists($currentSavedFileName)) {
                $this->update($jsonData, $currentSavedFileName);
            }

            // remove old file
            Storage::delete($oldFileName);


        } elseif (!$this->checkImageExists($website)) {
            $currentSavedFileName = $this->saveFileDisk($jsonData['content'], $jsonData['mime-type'], $website);
            if ($currentSavedFileName) {
                $jsonData['filename'] = $currentSavedFileName;
                $this->store($jsonData);
            }
        } else {
            echo 'already updated';
        }
    }

    //save ss data on database
    public function store($jsonData)
    {
        $storedImage = Screenshot::create($jsonData);
    }

    // update ss data on database
    public function update($jsonData, $fileName)
    {
        $affectedRows = Screenshot::where('website', '=', $jsonData['website'])->update(['filename' => $fileName]);
    }

    //save ss file into disk
    public function saveFileDisk($base64String, $fileExtension, $website)
    {
        $fileExtension = getBase64FileExtension($fileExtension);
        $dataToDecode = extractFileEncodedString($base64String);
        $decodedData = base64_decode($dataToDecode);
        $fileName = Str::uuid() . '.' . $fileExtension;
        Storage::disk('local')->put($fileName, $decodedData);
        return $fileName;
    }
}
