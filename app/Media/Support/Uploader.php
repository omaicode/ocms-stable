<?php
namespace App\Media\Support;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\UploadedFile;
use App\Media\Abstracts\MediaAbstract;

class Uploader extends MediaAbstract
{
    /**
     * 
     * @param UploadedFile $uploaded_file 
     * @return Illuminate\Support\Collection 
     */
    public function upload(UploadedFile $uploaded_file)
    {
        $this->files->add($uploaded_file);
        return $this->store();
    }

    /**
     * 
     * @param UploadedFile[] $uploaded_file s
     * @return Illuminate\Support\Collection 
     */
    public function uploadMultiple(array $uploaded_files)
    {
        foreach($uploaded_files as $uploaded_file) {
            if($uploaded_file instanceof UploadedFile) {
                $this->files->add($uploaded_file);
            }
        }

        return $this->store();
    }

    /**
     * 
     * @param string $path 
     * @return bool 
     * @throws BindingResolutionException 
     */
    public function createFolder(string $path)
    {
        if($this->disk()->exists($path)) {
            return true;
        }
        
        return $this->disk()->makeDirectory($path);
    }

    /**
     * 
     * @param string $from 
     * @param string $to 
     * @return bool 
     * @throws BindingResolutionException 
     */
    public function move(string $from, string $to)
    {
        if(!$this->disk()->exists($from)) {
            return false;
        }

        if(!$this->disk()->exists($to)) {
            $this->disk()->makeDirectory($to);
        }
        
        $dest = ltrim(rtrim($to, '/').'/'.ltrim($from, '/'), '/');
        $result = $this->disk()->move($from, $dest);

        if($media = $this->model()->where('file_name', ltrim($from, '/'))->first()) {
            if($result) {
                $media->update([
                    'file_name' => $dest
                ]);
            }
        }

        return $result;
    }

    public function remove(string $path)
    {
        if(!$this->disk()->exists($path)) {
            return false;
        }

        if($media = $this->model()->where('file_name', ltrim($path, '/'))->first()) {
            $media->delete();
        }

        if($this->disk()->getMetadata($path)['type'] == 'dir') {
            return $this->disk()->deleteDirectory($path);
        } else {
            return $this->disk()->delete($path);
        }
    }
}