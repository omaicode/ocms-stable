<?php
namespace App\Form\Fields;

use App\Form\Fields\Traits\HasPlainInput;

class Textarea extends BaseField
{
    use HasPlainInput;
    
    protected $view = 'admin.form.fields.textarea';
    protected $rows = 3;
    
    /**
     * Render this filed.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $this->addVariables(['rows' => $this->rows]);
        $this->defaultAttribute('id', $this->id)
            ->defaultAttribute('name', $this->elementName ?: $this->formatName($this->column))
            ->defaultAttribute('value', old($this->elementName ?: $this->column, $this->value()))
            ->defaultAttribute('placeholder', $this->getPlaceholder());

        return parent::render();
    }

    public function rows(int $num = 3)
    {
        $this->rows = $num;

        return $this;
    }
}