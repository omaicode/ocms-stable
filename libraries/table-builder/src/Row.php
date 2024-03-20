<?php
namespace OCMS\TableBuilder;

use Illuminate\Database\Eloquent\Model;
use OCMS\TableBuilder\Traits\HtmlTrait;

class Row
{
    use HtmlTrait;
    
    public $attributes = [];
    private $resolves   = [];
    private $row = [];

    public function __construct(Model $item, array $resolves = [], array $attributes = [])
    {
        $this->row        = $item;
        $this->resolves   = $resolves;
        $this->attributes = $attributes;
    }

    public function __get($name)
    {
        $value   = isset($this->row->$name) ? $this->row->$name : '';
        
        if(!isset($this->$name)) {
            if(isset($this->resolves[$name])) {
                return call_user_func($this->resolves[$name], $this->row);
            } else {
                return $value;
            }
        }

        return $this->$name;
    }

    /**
     * Convert to array
     * 
     * @return array 
     */
    public function toArray()
    {
        $attrs = $this->row->toArray();
        $attrs['attributes'] = $this->attributes;

        return $attrs;
    }    
}