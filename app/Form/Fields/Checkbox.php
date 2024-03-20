<?php
namespace App\Form\Fields;

class Checkbox extends BaseField
{
    /**
     * @var string
     */
    protected $view = "admin.form.fields.checkbox";

    /**
     * @var mixed
     */
    protected $checkValue = "1";

    /**
     * @var bool
     */
    protected bool $is_nullable = false;

    /**
     * Render this filed.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $this->attribute([
            'name' => $this->column,
            'id' => $this->column,
            'value' => $this->checkValue
        ]);

        if($this->value === $this->checkValue || $this->is_nullable && $this->value !== null) {
            $this->attribute(['checked' => 'checked']);
        }

        return parent::render();
    }

    /**
     * @param $is_nullable
     * @return $this
     */
    public function nullable(bool $is_nullable = true)
    {
        $this->is_nullable = $is_nullable;
        return $this;
    }
}
