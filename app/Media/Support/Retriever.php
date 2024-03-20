<?php
namespace App\Media\Support;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use League\Flysystem\FileNotFoundException;
use App\Media\Abstracts\MediaAbstract;

class Retriever extends MediaAbstract
{
    /**
     * 
     * @param Collection $data 
     * @param mixed $dir 
     * @param bool $recursive 
     * @return void 
     * @throws BindingResolutionException 
     * @throws FileNotFoundException 
     */
    protected function getTree(Collection &$data, $dir = null, $recursive = true, $flatten = false)
    {
        $directories = $this->disk()->directories($this->storePath($dir));
        $files       = $this->disk()->files($this->storePath($dir));

        if($directories) {
            foreach($directories as $directory) {
                $splited = explode('/', $directory);
                $name    = $splited[count($splited) - 1];
                $dir_data = [
                    'uuid'   => (string)Str::uuid(),
                    'name'      => $name,
                    'path'      => $directory,
                    'is_dir'    => true,
                    'last_modified_at' => Carbon::createFromTimestamp($this->disk->getTimestamp($directory))->format('Y-m-d'),
                ];

                if($recursive) {
                    if($flatten) {
                        $data->add($dir_data);
                        $this->getTree($data, $directory);
                    } else {
                        $childs  = new Collection;
                        $this->getTree($childs, $directory);

                        $dir_data['childrens'] = $childs;
                        $data->add($dir_data);
                    }
                } else {
                    $data->add($dir_data);                    
                }
            }
        }

        if($files) {
            foreach($files as $file) {
                $splited   = explode('/', $file);
                $name      = $splited[count($splited) - 1];
                $file_info = pathinfo($file);
                $uuid = optional($this->model()->where('file_name', ltrim($file, '/'))->first())->uuid;

                $data->add([
                    'uuid'   => $uuid ?: (string)Str::uuid(),
                    'name'   => $name,
                    'path'   => ltrim($file, '/'),
                    'is_dir' => false,
                    'ext'    => $file_info['extension'],
                    'size'   => File::getHumanReadableSize($this->disk->getSize($file)),
                    'last_modified_at' => Carbon::createFromTimestamp($this->disk->getTimestamp($file))->format('Y-m-d'),
                    'mime_type' => $this->disk()->getMimetype($file),
                    'url' => $this->disk()->url($file),
                    'stored_db' => $uuid ? true : false
                ]);
            }
        }        
    }

    /**
     * Get all files & folder
     * 
     * @var string $path
     * @var bool $recursive
     * @return Collection 
     * @throws BindingResolutionException 
     * @throws FileNotFoundException 
     */
    public function all(string $path = '/', $recursive = false)
    {
        $data = new Collection;
        $this->getTree($data, $path, $recursive);

        return $data;
    }

    /**
     * Find media by id
     * 
     * @param mixed $id 
     * @return Collection 
     */
    public function findById($id)
    {
        return $this->model()
        ->where('id', $id)
        ->first();
    }

    /**
     * Find media by uuid
     * 
     * @param mixed $id 
     * @return Collection 
     */
    public function findByUuid($uuid)
    {
        return $this->model()
        ->where('uuid', $uuid)
        ->first();
    }

    /**
     * Find media by name
     * 
     * @param mixed $id 
     * @return Collection 
     */    
    public function findByName(string $name)
    {
        return $this->model()
        ->where('name', 'LIKE', "{$name}%")
        ->first();        
    }

    /**
     * Get media
     * 
     * @param mixed $query 
     * @return Collection 
     */    
    public function get(&$query = null)
    {
        $model = $this->model();     

        if($query && is_callable($query)) {
            $model->where(fn($q) => $query($q));
        }

        return $model->get();
    }

    /**
     * Check file exists
     * 
     * @var string $path
     * @return void 
     */
    public function fileExists($path)
    {
        return $this->disk()->exists($path);
    }
    
    public function fileUrl($path = null)
    {
        return $this->disk()->url($path);
    }
}