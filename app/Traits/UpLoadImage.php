<?php

namespace App\Traits;

trait UpLoadImage
{

    protected function uploadeFile($file, $name = "", $id = "")
    {
        $file_name = time() . 'file.' . $file->getClientOriginalExtension();
        $path = 'public/files/matrerials/' . $name . $id;
        $stored_path = $file->storeAs($path, $file_name);

        return $stored_path;
    }
}
