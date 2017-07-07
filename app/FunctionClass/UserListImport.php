<?php
/**
 * Created by PhpStorm.
 * User: Surya
 * Date: 05/07/2017
 * Time: 13:08
 */

namespace App\FunctionClass;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Files\ExcelFile;

class UserListImport extends ExcelFile {
    public function getFile()
    {
        $file = Input::file('excel_file');
        $path = Storage::url('upload/superuser');
        $file->storeAs($path, 'upload-file.xlsx');

        $filepath = storage_path() . '/app' . Storage::url('upload/superuser/upload-file.xlsx');

        return $filepath;
    }

    public function getFilters()
    {
        return [
            'chunk'
        ];
    }
}