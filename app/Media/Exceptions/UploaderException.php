<?php

namespace App\Media\Exceptions;

use Exception;

class UploaderException extends Exception
{
    public static function storeMediaIsEmpty()
    {
        return new static("Please add at least 1 file to upload.");
    }

    public static function storeMediaUploadFail(string $file_name)
    {
        return new static("[Error][Uploader] The file {$file_name} can't not be upload.");
    }

    public static function storeMediaModelUploadFail(string $file_name)
    {
        return new static("[Error][Uploader] The file {$file_name} can't not be save into database.");
    }
}
