<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Files\ExcelFile;

class EventMemberImport extends ExcelFile
{

    public function getFile()
    {
        $file = Input::file('member_file');

        $filpath = $file->getRealPath();
        $filename = $file->getClientOriginalName();

        return $filpath;
    }

}
