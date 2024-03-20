<?php
namespace App\Media\Abstracts;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Media\Exceptions\UploaderException;
use App\Models\Media;

abstract class MediaAbstract
{
    protected ?Collection $files = null;
    protected FilesystemAdapter $disk;
    protected $media_store_path = '';

    public function __construct()
    {
        $this->files = new Collection;
        $this->disk  = Storage::disk($this->getDiskName());
        $this->media_store_path = rtrim(config('media.prefix', 'default'), '/');
    }

    /**
     * 
     * @return mixed 
     * @throws BindingResolutionException 
     */
    protected function getDiskName()
    {
        return config('media.disk_name', 'public');
    }

    /**
     * 
     * @return FilesystemAdapter 
     * @throws BindingResolutionException 
     * @throws InvalidArgumentException 
     */
    protected function disk()
    {
        return Storage::disk($this->getDiskName());
    }

    /**
     * 
     * @param string|null $path 
     * @return string 
     */
    protected function storePath(string $path = null)
    {
        return rtrim($this->media_store_path, '/').'/'.ltrim($path, '/');
    }

    /**
     * 
     * @param string $file_name 
     * @return array 
     */
    protected function genFileNameBeforeUpload(string $file_name)
    {
        $ext  = pathinfo($file_name, PATHINFO_EXTENSION);
        $name = substr($file_name, 0, strlen($file_name) - (strlen($ext) + 1));
        $full_path = $this->storePath($file_name);

        if(file_exists($this->disk->exists($full_path))) {
            $name = sprintf("%s_%s", $name, time());
        }

        $file_name = sprintf('%s.%s', $name, $ext);

        return compact('name', 'ext', 'file_name');
    }

    /**
     * 
     * @return Collection 
     * @throws UploaderException 
     * @throws BindingResolutionException 
     */
    protected function store()
    {
        if($this->files->count() <= 0) {
            throw UploaderException::storeMediaIsEmpty();
        }

        $media_list = new Collection;
        /** @var UploadedFile $file */
        foreach($this->files as $file) {
            $file_info  = $this->genFileNameBeforeUpload($file->getClientOriginalName());
            $file_info['file_name']  = Str::replace(" ", "_", $file_info['file_name']);
            $full_path  = $this->storePath($file_info['file_name']); 

            if($file->storePubliclyAs($this->storePath(), $file_info['file_name'], config('media.disk_name', 'public')) !== false) {
                $media = $this->model()->create([
                    'uuid'      => (string)Str::uuid(),
                    'name'      => $file_info['name'],
                    'file_name' => ltrim($full_path, '/'),
                    'mime_type' => $file->getClientMimeType(),
                    'disk'      => $this->getDiskName(),
                    'size'      => $file->getSize()
                ]);

                if($media) {
                    $media_list->add($media);
                } else {
                    throw UploaderException::storeMediaModelUploadFail($file_info['file_name']);
                }
            } else {
                throw UploaderException::storeMediaUploadFail($file_info['file_name']);
            }
        }

        return $media_list;
    }    

    /**
     * 
     * @return Media 
     * @throws BindingResolutionException 
     */
    public function model()
    {
        return app(Media::class);
    }    

    /**
     * 
     * @return this 
     */
    public function setDisk(string $disk_name)
    {
        $this->disk = Storage::disk($disk_name);
        return $this;
    }

    /**
     * 
     * @param string $path 
     * @return $this 
     */
    public function setSavePath(string $path)
    {
        $this->media_store_path = $path;
        return $this;
    }
}