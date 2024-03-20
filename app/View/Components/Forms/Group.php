<?php
namespace App\View\Components\Forms;
use Illuminate\View\Component;
class Group extends Component
{
    public $label;
    public $error;
    public $help;
    public $required;
    public $readonly;
    public $disabled;
    public $multiple;
    public $checked;
    public $mode;
    public $name;
    public $value;
    public $placeholder;
    public $attrs;
    public $type;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $mode = 'input', 
        $label = null, 
        $error = null, 
        $help = null, 
        $required = false, 
        $readonly = false, 
        $disabled = false, 
        $multiple = false, 
        $checked = null, 
        $name = '', 
        $value = '', 
        $placeholder = '',
        $type = 'text',
        array $attrs = []
    )
    {
        $this->label = $label;
        $this->error = $error;
        $this->help = $help;
        $this->required = $required;
        $this->readonly = $readonly;
        $this->disabled = $disabled;
        $this->multiple = $multiple;
        $this->checked = $checked;
        $this->mode = $mode;
        $this->name = $name;
        $this->attrs = $attrs;
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    { 
        return view('admin.components.forms/group');
    }
}
