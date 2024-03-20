<?php
namespace App\View\Components\Forms;

use AdminAsset;
use Illuminate\View\Component;
class Thumbnail extends Component
{
    public $name;
    public $label;
    public $value;
    public $required;
    public $disabled;
    public $placeholder;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name = '', $value = '', $label = '', $placeholder = '', $required = false, $disabled = false)
    {
        $this->name = $name;
        $this->label = $label;
        $this->value = $value;
        $this->required = $required;
        $this->disabled = $disabled;
        $this->placeholder = $placeholder;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('admin.components.forms/thumbnail');
    }
}
