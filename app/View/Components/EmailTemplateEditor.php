<?php
namespace App\View\Components;

use AdminAsset;
use Illuminate\View\Component;

class EmailTemplateEditor extends Component
{
    public $value;
    public $name;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($value = '', $name)
    {
        $this->value = $value;
        $this->name = $name;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        AdminAsset::addCustomScript($this->getScript());
        return view('admin.components.email-template-editor');
    }

    public function getScript()
    {
        $name = $this->name;
        $route = route("admin.ajax.email-template.preview");
        
        return <<<SCRIPT
        function togglePreview_{$name}(editor) {
            var cm = editor.codemirror;
            var wrapper = cm.getWrapperElement();
            var toolbar_div = wrapper.previousSibling;
            var toolbar = editor.options.toolbar ? editor.toolbarElements.preview : false;
            var preview = wrapper.lastChild;
            if(!preview || !/editor-preview/.test(preview.className)) {
                preview = document.createElement("div");
                preview.className = "editor-preview";
                wrapper.appendChild(preview);
            }
            if(/editor-preview-active/.test(preview.className)) {
                preview.className = preview.className.replace(
                    /\s*editor-preview-active\s*/g, ""
                );
                if(toolbar) {
                    toolbar.className = toolbar.className.replace(/\s*active\s*/g, "");
                    toolbar_div.className = toolbar_div.className.replace(/\s*disabled-for-preview*/g, "");
                }
            } else {    
                // When the preview button is clicked for the first time,
                // give some time for the transition from editor.css to fire and the view to slide from right to left,
                // instead of just appearing.
                setTimeout(function() {
                    preview.className += " editor-preview-active";
                }, 1);
                if(toolbar) {
                    toolbar.className += " active";
                    toolbar_div.className += " disabled-for-preview";
                }
            }
    
            var view = editor.element.getAttribute('data-view')
    
            if(view) {
                axios.post('{$route}', {view})
                .then(function({data}) {
                    preview.innerHTML = data.data[0]
                })
                .catch(function(err) {
                    Notyf.error("Something went wrong! Please make sure all variables is optional to render a preview.")
                })
            } else {
                preview.innerHTML = editor.options.previewRender(editor.value(), preview);
            }
    
            // Turn off side by side if needed
            var sidebyside = cm.getWrapperElement().nextSibling;
            if(/editor-preview-active-side/.test(sidebyside.className))
                toggleSideBySide(editor);
        }
        
        window["templateEditor_{$name}"] = new SimpleMde({ 
            element: document.querySelector('textarea[name="{$name}"]'), 
            autofocus: true,
            spellChecker: false,
            forceSync: true,  
            toolbar: [
                'bold',
                'italic',
                'heading',
                '|',
                'strikethrough',
                'unordered-list',
                'ordered-list',
                '|',
                'link',
                'image',
                'table',
                'horizontal-rule',
                '|',
                'fullscreen',
                {
                    name: "preview",
                    action: togglePreview_{$name},
                    className: "fa fa-eye no-disable",
                    title: "Toggle Preview",
                    default: true                
                }
            ]    
        });
        SCRIPT;
    }
}
