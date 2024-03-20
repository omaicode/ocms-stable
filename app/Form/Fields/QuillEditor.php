<?php
namespace App\Form\Fields;

use App\Form\Fields\Traits\HasPlainInput;

class QuillEditor extends BaseField
{
    use HasPlainInput;

    protected $view = 'admin.form.fields.quill-editor';
    protected static $css = [
        '/assets/admin/vendors/quill/css/monokai-sublime.css',
        '/assets/admin/vendors/quill/css/quill.snow.css',
    ];
    protected static $js = [
        '/assets/admin/vendors/quill/js/highlight.js',
        '/assets/admin/vendors/quill/js/quill.min.js',
        '/assets/admin/vendors/quill/js/quill.html-editor.js',
    ];

    /**
     * Render this filed.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $this->setScript($this->script());
        $this->defaultAttribute('id', $this->id)
            ->defaultAttribute('name', $this->elementName ?: $this->formatName($this->column))
            ->defaultAttribute('placeholder', $this->getPlaceholder());

        return parent::render();
    }

    protected function script()
    {
        $placeholder = $this->placeholder;
        $id = $this->id;

        return <<<SCRIPT
        var Block = Quill.import('blots/block');
        Block.tagName = 'DIV';
        Quill.register(Block, true);
        Quill.register("modules/htmlEditButton", htmlEditButton);
        var txtElem = document.getElementById('{$id}');
        var quill = new Quill('#editor-container', {
            modules: {
              syntax: true,
              toolbar: '#toolbar-container',
              htmlEditButton: {
                buttonHTML: '<i class="fas fa-code"></i>'
              }
            },
            placeholder: '{$placeholder}',
            theme: 'snow'
        });

        quill.on('text-change', function(delta, oldDelta, source) {
            txtElem.value = quill.root.innerHTML;
            txtElem.dispatchEvent(new Event("input"));
        });
        SCRIPT;
    }
}
