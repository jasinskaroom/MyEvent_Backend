<?php

namespace App\Http\Controllers;

use Auth;
use Storage;
use Response;
use Illuminate\Http\Request;

class ImageViewController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function viewImage($storage, $filename)
    {
        if (Storage::disk($storage)->exists($filename) == false) {
            return null;
        }

        $file = Storage::disk($storage)->get($filename);
        $mime = 'image/' . explode('.' ,$filename)[1];

        $headers = [
            'Content-Type' => $mime
        ];

        return Response::make($file, 200, $headers);
    }
}
