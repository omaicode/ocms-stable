<?php
namespace App\Form\Tools;

use Closure;
use App\Form\Fields\BaseField;

class Avatar extends BaseField
{    
    protected $preview;
    protected $accept  = 'image/jpg,image/jpeg,image/png,image/gif';

    public function __construct($column, $args = [])
    {
        $this->initView();
        parent::__construct($column, $args);
    }
    
    /**
     * @param string $attribute
     * @param string $value
     *
     * @return $this
     */
    protected function defaultAttribute($attribute, $value)
    {
        if (!array_key_exists($attribute, $this->attributes)) {
            $this->attribute($attribute, $value);
        }

        return $this;
    }    

    /**
     * Render this filed.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $this->id = uniqid($this->id.'_');
        $this
            ->defaultAttribute('name', $this->elementName ?: $this->formatName($this->column))
            ->defaultAttribute('accept', $this->accept)
            ->setScript($this->getAvatarScript());

        if($this->preview instanceof Closure) {
            $callback = $this->preview;
            $this->preview = $callback($this->form);
        }

        return parent::render();
    }

    /**
     * Set preview url
     * 
     * @param string|Closure $url 
     * @return $this 
     */
    public function preview($url)
    {
        $this->preview = $url;

        return $this;
    }

    /**
     * Set accept files mimes
     * 
     * @param mixed $url 
     * @return $this 
     */
    public function accept(string $mimes)
    {
        $this->accept = $mimes;

        return $this;
    }

    /**
     * Get the view variables of this field.
     *
     * @return array
     */
    public function variables(): array
    {
        return array_merge($this->variables, [
            'id'          => $this->id,
            'name'        => $this->elementName ?: $this->formatName($this->column),
            'help'        => $this->help,
            'class'       => $this->getElementClassString(),
            'value'       => $this->value(),
            'label'       => $this->label,
            'viewClass'   => $this->getViewElementClasses(),
            'column'      => $this->column,
            'errorKey'    => $this->getErrorKey(),
            'attributes'  => $this->formatAttributes(),
            'preview'     => $this->preview,
            'placeholder' => $this->placeholder
        ]);
    }    

    protected function initView()
    {
        if (empty($this->view)) {
            $this->view = 'admin.form.tools.avatar';
        }        
    }
    
    protected function getAvatarScript()
    {
        $messages = [
            'invalid_type' => __('messages.avatar.invalid_type'),
            'invalid_size' => __('messages.avatar.invalid_size'),
        ];

        return <<<SCRIPT
        (function() {
            const btn    = document.getElementById('{$this->id}_button')
            const avatar = document.getElementById('{$this->id}');

            btn.addEventListener('click', function() {
                avatar.click()
            });

            avatar.addEventListener('change', function() {
                const file   = avatar.files[0]
                const mimes  = avatar.getAttribute('accept').split(',')

                if(!mimes.includes(file.type)) {
                    Notyf.error('{$messages['invalid_type']}')
                    avatar.value = ''
                    return
                }

                if(file.size > 3072000) {
                    Notyf.error('{$messages['invalid_size']}')
                    avatar.value = ''
                    return                
                }

                const preview = avatar.parentNode.querySelector('#{$this->id}_preview')
                preview.setAttribute('src', URL.createObjectURL(file))
            })
        })()
        SCRIPT;
    }
}