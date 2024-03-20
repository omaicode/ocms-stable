<?php

namespace App\Form;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use App\Form\Tools\Avatar;
use App\Form\Tools\Media;
use App\Form\Tools\Select;

/**
 * @method App\Form\Tools\Avatar        avatar($column, $label = '')
 * @method App\Form\Tools\Select        select($column, $label = '')
 * @method App\Form\Tools\Media         media($column, $label = '', $model_class)
 **/
class Tools implements Renderable
{
    /**
     * @var Builder
     */
    protected $form;

    /**
     * Tools.
     *
     * @var Collection
     */
    protected $tools;

    /**
     * Available tools
     * 
     * @var string[]
     */
    protected $avaiableTools = [
        'avatar' => Avatar::class,
        'select' => Select::class,
        'media'  => Media::class,
    ];

    /**
     * Create a new Tools instance.
     *
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->form = $builder;
        $this->tools = new Collection();
    }

    /**
     * Add a tool.
     *
     * @param mixed $tool
     *
     * @return $this
     */
    public function add($tool)
    {
        $this->tools->push($tool);

        return $this;
    }

    /**
     * Get parent form of tool.
     *
     * @return Builder
     */
    public function form()
    {
        return $this->form;
    }

    /**
     * Render tools.
     *
     * @return string
     */
    public function render()
    {
        $output = '';

        foreach ($this->tools as $tool) {
            $output .= $tool->render();
        }

        return $output;
    }

    public function __call($name, $arguments)
    {
        if(in_array($name, array_keys($this->avaiableTools))) {
            $column = Arr::get($arguments, 0, '');
            $tool = new $this->avaiableTools[$name]($column, array_slice($arguments, 1));
            
            if(method_exists($tool, 'setBuilder')) {
                $tool->setBuilder($this->form());
            }

            $this->add($tool);

            return $tool;
        }

        return false;
    }

    public function all()
    {
        return $this->tools;
    }

    public function hasTool($tool_name)
    {
        if(!isset($this->avaiableTools[$tool_name])) {
            return false;
        }

        $tool_class = $this->avaiableTools[$tool_name];

        foreach($this->tools as $tool) {
            if(get_class($tool) == $tool_class) {
                return true;
            }
        }

        return false;
    }
}