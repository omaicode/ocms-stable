<?php
namespace App\Form\Tools;

use App\Form\Builder;
use App\Form\Fields\Select as SelectParent;

class Select extends SelectParent
{
    protected $view = 'admin.form.tools.select';

    /**
     * Render this filed.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        if($this->form) {
            $this->value = $this->form->model()[$this->column];
        }

        return parent::render();
    }

    public function setBuilder(Builder $builder)
    {
        $this->form = $builder->getForm();

        return $this;
    }
}
