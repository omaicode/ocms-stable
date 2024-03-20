<?php
namespace App\Supports;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class Media
{
    protected static $disk = 'public';
    private static $_instance = null;

    public static function getInstance ()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public static function upload(UploadedFile $file, $folder = 'images', $ext = 'png', $options = [])
    {
        $uuid       = (string)Str::uuid();
        $file_name  = sprintf("%s.%s", $uuid, $ext);

        $saved = $file->storeAs(
            $folder,
            $file_name,
            array_merge(['disk' => self::$disk], $options)
        );

        if(!$saved) {
            return false;
        }

        return [
            'file_name' => $file_name,
            'save_path' => sprintf('%s/%s', $folder, $file_name),
            'disk'      => self::$disk
        ];
    }

    public function setDisk($disk)
    {
        self::$disk = $disk;

        return $this;
    }
}