<?php
namespace OCMS\TableBuilder;

use Closure;
use Illuminate\Database\Eloquent\Model;
use OCMS\TableBuilder\Traits\HtmlTrait;

class Column
{
    use HtmlTrait;
    
    public $field;
    public $label;
    public $display;
    public $width;
    public array $attributes = [];
    public array $row_attributes = [];

    public function __construct(string $field, string $label, ?Closure $display = null)
    {
        $this->field = $field;
        $this->label = $label;
        $this->display = $display;
    }

    /**
     * Render attributes to HTML
     * @return string 
     */
    public function renderAttributes()
    {
        $html = '';
        foreach($this->attributes as $name => $value) {
            if(is_array($value) && $name == 'style') {
                $styles = '';

                foreach($value as $key => $value) {
                    $styles .= "{$key}:{$value};";
                }

                $html .= " {$name}='{$styles}'";
            } else {
                $html .= " {$name}='{$value}'";
            }
        }

        return $html;
    }

    /**
     * Get column value
     * 
     * @param mixed $item 
     * @return mixed 
     */
    public function getValue($item)
    {
        $field = $this->field;
        return $item->$field;
    }

    /**
     * Convert to array
     * 
     * @return array 
     */
    public function toArray()
    {
        $vars   = get_class_vars(get_class($this));
        $result = [];

        foreach($vars as $name => $value) {
            $result[$name] = $this->$name;
        }

        return $result;
    }
}