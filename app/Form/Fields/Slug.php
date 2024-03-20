<?php
namespace App\Form\Fields;

class Slug extends BaseField
{
    protected $view   = 'admin.form.fields.slug';
    protected $column = [];

    protected $slug_name = 'slug';

    protected static $css = [
        '/assets/admin/vendors/bootstrap-editable/css/bootstrap-editable.css?v=1.0.0',
    ];

    protected static $js = [
        '/assets/admin/vendors/bootstrap-editable/js/bootstrap-editable.js',
        '/assets/admin/vendors/bootstrap-editable/js/slugify.js',
        '/assets/admin/vendors/bootstrap-editable/js/transliteration.js',
    ];

    /**
     * Field constructor.
     *
     * @param       $column
     * @param array $arguments
     */
    public function __construct($column = '', $arguments = [])
    {
        $this->column['name'] = $column;
        $this->column['slug'] = $arguments[0];

        array_shift($arguments);
        $this->label = $this->formatLabel($arguments);
        $this->id = $this->formatId($this->column);
    }

    protected function script()
    {
        $locale = app()->getLocale();

        return <<<JS
        $(function() {
            var slugManualChanged = false;

            $('input[name="{$this->id['name']}"]').on('input', function(e) {
                if(slugManualChanged) return;

                const val = slugify(transliterate(e.target.value), {
                    replacement: '-'
                }) || 'no-slug';
                $('#{$this->id['slug']}').text(val.toLowerCase())
                $('input[name="{$this->id['slug']}"]').val(val.toLowerCase())
            })

            $('#{$this->id['slug']}').editable({
                type: 'text',
                mode: 'inline',
                emptytext: 'no-slug'
            })

            $('#{$this->id['slug']}').on('save', function(e, params) {
                slugManualChanged = true

                const val = slugify(params.newValue, '-')
                $('input[name="{$this->id['slug']}"]').val(val)
            })

            $('#{$this->id['slug']}').on('hidden', function(e, reason) {
                $('#{$this->id['slug']}').text($('input[name="{$this->id['slug']}"]').val() || 'no-slug')
            })
        })
        JS;
    }

    /**
     * Render this filed.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $this->setScript($this->script());

        return parent::render();
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($value)
    {
        if ($value === '') {
            $value = null;
        }

        return $value;
    }
}
