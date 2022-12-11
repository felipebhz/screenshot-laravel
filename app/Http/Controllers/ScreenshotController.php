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
    /**
     * Retrieve all screenshots available for the application.
     *
     * @return string
     */
    public function index()
    {
        return response()->json(Screenshot::all());
    }

    /**
     * Retrieve data for one screenshot based on website name and handle the response.
     *
     * @return string
     */
    public function getScreenshot($website)
    {
        $screenshotData = false;
        if (Screenshot::select('content')->where('website', '=', $website)->first()) {
            $screenshotData = Screenshot::select('content')->where('website', '=', $website)->first();
            $screenshotData = response()->json($screenshotData->content);
        }
        if (!$screenshotData) {
            $this->updateScreenshot($website);
            $screenshotData = Screenshot::select('content')->where('website', '=', $website)->first();
            $screenshotData = response()->json($screenshotData->content);
        } elseif ($screenshotData && $this->checkImageNeedsUpdate($website)) {
            $this->updateScreenshot($website);
        }
        return $screenshotData;
    }

    /**
     * Check if screenshot data needs to updated.
     * 
     * @param string $website
     * 
     * @return bool
     */
    public function checkImageNeedsUpdate($website)
    {
        return Screenshot::select('id', 'website')->where('website', '=', $website)->where('updated_at', '<', Carbon::now()->subDays(3))->get()->count() > 0;
    }

    /**
     * Check if screenshot is already in the system.
     *
     * @param string $website
     * 
     * @return bool
     */
    public function checkImageExists($website)
    {
        $recordsFound = DB::table('screenshots')->select('filename')->where('website', '=', $website)->count();
        $exists = ($recordsFound > 0) ? true : false;
        return $exists;
    }

    /**
     * Check the current screenshot's filename for a given website.
     *
     * @param string $website
     * 
     * @return string
     */
    public function getCurrentWebsiteFilename($website)
    {
        return Screenshot::where('website', $website)->first()->filename;
    }

    /**
     * Check the current status of screenshot for a given website 
     * and handle acordingly.
     * 
     * A simple API mock has been used in this function to provide a more
     * realistic request/response to comply with the needs of the app.
     * 
     * Service used: mockable.io | Response received is screenshot data in
     * base64 string and some data from the website.
     *
     * @param string $website
     * 
     * @return string
     */
    public function updateScreenshot($website)
    {
        $response = Http::post('http://demo4455834.mockable.io/v1/screenshot/' . $website . '');

        $jsonData = $response->json();
        $jsonData['website'] = $website;

        if ($this->checkImageExists($website) && $this->checkImageNeedsUpdate($website)) {
            $oldFileName = $this->getCurrentWebsiteFilename($website);
            $currentSavedFileName = $this->saveFileDisk($jsonData['content'], $jsonData['mime-type'], $website);
            if (Storage::disk('local')->exists($currentSavedFileName)) {
                $this->update($jsonData, $currentSavedFileName);
                Storage::delete($oldFileName);
            }
            
        } elseif (!$this->checkImageExists($website)) {
            $currentSavedFileName = $this->saveFileDisk($jsonData['content'], $jsonData['mime-type'], $website);
            if ($currentSavedFileName) {
                $jsonData['filename'] = $currentSavedFileName;
                $this->store($jsonData);
            }
        } else {
            $alreadyUpdated = ['status' => 'Updated'];
            return json_encode($alreadyUpdated);
        }
    }

    /**
     * Write website's screenshot data on database.
     *
     * @param string $jsonData
     * 
     * @return void
     */
    public function store($jsonData)
    {
        $storedImage = Screenshot::create($jsonData);
    }

    /**
     * Update website's screenshot data on database.
     *
     * @param string $jsonData
     * 
     * @return void
     */
    public function update($jsonData, $fileName)
    {
        $affectedRows = Screenshot::where('website', '=', $jsonData['website'])->update(['filename' => $fileName]);
    }

    /**
     * Writes website's screenshot data on database.
     *
     * Str::uuid has been used to create unique filenames
     * and avoid filenames conflicts.
     * 
     * @param string $base64String
     * @param string $fileExtension
     * @param string $website
     * 
     * @return string
     */
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
