<?php
namespace App\Form\Fields;

use App\Form\Fields\Traits\HasPlainInput;
use Illuminate\Support\Str;

class Ckeditor extends BaseField
{
    use HasPlainInput;
    
    protected $view = 'admin.form.fields.ckeditor';
    protected static $css = [
        '/assets/admin/vendors/ckeditor/css/ckeditor.css'
    ];
    protected static $js = [
        '/assets/admin/vendors/ckeditor/js/ckeditor.js',
        '/assets/admin/vendors/ckeditor/js/ckeditor.plugins.js',
    ];
    
    /**
     * Render this filed.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $this->id = $this->id . '_' . substr(Str::uuid(), 0, 8);
        $this->setScript($this->script());
        $this->defaultAttribute('id', $this->id)
            ->defaultAttribute('name', $this->elementName ?: $this->formatName($this->column))
            ->defaultAttribute('placeholder', $this->getPlaceholder());

        return parent::render();
    }

    protected function script()
    {
        return <<<SCRIPT
        Ckeditor
        .create( document.querySelector( '#{$this->id}' ), {toolbar: {shouldNotGroupWhenFull: true}})
        .then( editor => {
            window.ckeditor_{$this->id} = editor;
            editor.editing.view.document.on(
                'enter',
                ( evt, data ) => {
                    editor.execute('shiftEnter');
                    //Cancel existing event
                    data.preventDefault();
                    evt.stop();
             }, {priority: 'high' });
        } )
        .catch( error => {
            console.error( 'Oops, something went wrong!' );
            console.error( error );
        } );        
        SCRIPT;
    }
}