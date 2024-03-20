<?php

namespace App\Form;

use App\Facades\AdminAsset;
use App\Form\Form;
use App\Form\Fields\Hidden;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Form\Fields\BaseField;

/**
 * Class Builder.
 */
class Builder
{
    /**
     *  Previous url key.
     */
    const PREVIOUS_URL_KEY = '_previous_';

    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var Form
     */
    protected $form;

    /**
     * @var
     */
    protected $action;

    /**
     * @var Collection
     */
    protected $fields;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var Tools
     */
    protected $tools;

    /**
     * Modes constants.
     */
    const MODE_EDIT = 'edit';
    const MODE_CREATE = 'create';
    const MODE_DELETE = 'delete';

    /**
     * Form action mode, could be create|view|edit.
     *
     * @var string
     */
    protected $mode = 'create';

    /**
     * @var array
     */
    protected $hiddenFields = [];

    /**
     * View for this form.
     *
     * @var string
     */
    protected $view = 'admin.form.form';

    /**
     * Form title.
     *
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $formClass;

    /**
     * Builder constructor.
     *
     * @param Form $form
     *
     * @return void
     */
    public function __construct(Form $form)
    {
        $this->form = $form;

        $this->fields = new Collection();

        $this->init();
    }

    /**
     * Do initialize.
     *
     * @return void
     */
    public function init()
    {
        $this->tools = new Tools($this);
        $this->formClass = 'model-form-'.uniqid();
    }

    /**
     * Get form tools instance.
     *
     * @return Tools
     */
    public function getTools()
    {
        return $this->tools;
    }

    /**
     * Get form footer instance.
     *
     * @return Footer
     */
    public function getFooter()
    {
        return $this->footer;
    }

    /**
     * Set the builder mode.
     *
     * @param string $mode
     *
     * @return void
     */
    public function setMode($mode = 'create')
    {
        $this->mode = $mode;
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * Returns builder is $mode.
     *
     * @param string $mode
     *
     * @return bool
     */
    public function isMode($mode): bool
    {
        return $this->mode === $mode;
    }

    /**
     * Check if is creating resource.
     *
     * @return bool
     */
    public function isCreating(): bool
    {
        return $this->isMode(static::MODE_CREATE);
    }

    /**
     * Check if is editing resource.
     *
     * @return bool
     */
    public function isEditing(): bool
    {
        return $this->isMode(static::MODE_EDIT);
    }

    /**
     * Set resource Id.
     *
     * @param mixed $id
     *
     * @return void
     */
    public function setResourceId($id)
    {
        $this->id = $id;
    }

    /**
     * Get Resource id.
     *
     * @return mixed
     */
    public function getResourceId()
    {
        return $this->id;
    }

    /**
     * @param int|null $slice
     *
     * @return string
     */
    public function getResource(int $slice = null): string
    {
        if ($this->mode === self::MODE_CREATE) {
            return $this->form->resource(-1);
        }
        if ($slice !== null) {
            return $this->form->resource($slice);
        }

        return $this->form->resource();
    }

    /**
     * Set form action.
     *
     * @param string $action
     *
     * @return void
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * Get Form action.
     *
     * @return string
     */
    public function getAction(): string
    {
        if ($this->action) {
            return $this->action;
        }

        if ($this->isMode(static::MODE_EDIT)) {
            return $this->form->resource().'/'.$this->id;
        }

        if ($this->isMode(static::MODE_CREATE)) {
            return $this->form->resource(-1);
        }

        return '';
    }

    /**
     * Set view for this form.
     *
     * @param string $view
     *
     * @return $this
     */
    public function setView($view): self
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Set title for form.
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get fields of this builder.
     *
     * @return Collection
     */
    public function fields(): Collection
    {
        return $this->fields;
    }

    /**
     * Get specify field.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function field($name)
    {
        return $this->fields()->first(function (BaseField $field) use ($name) {
            return $field->column() === $name;
        });
    }

    /**
     * If the parant form has rows.
     *
     * @return bool
     */
    public function hasRows(): bool
    {
        return !empty($this->form->rows);
    }

    /**
     * Get field rows of form.
     *
     * @return array
     */
    public function getRows(): array
    {
        return $this->form->rows;
    }

    /**
     * @return array
     */
    public function getHiddenFields(): array
    {
        return $this->hiddenFields;
    }

    /**
     * @param Field $field
     *
     * @return void
     */
    public function addHiddenField(BaseField $field)
    {
        $this->hiddenFields[] = $field;
    }

    /**
     * Add or get options.
     *
     * @param array $options
     *
     * @return array|null
     */
    public function options($options = [])
    {
        if (empty($options)) {
            return $this->options;
        }

        $this->options = array_merge($this->options, $options);
    }

    /**
     * Get or set option.
     *
     * @param string $option
     * @param mixed  $value
     *
     * @return $this
     */
    public function option($option, $value = null)
    {
        if (func_num_args() === 1) {
            return Arr::get($this->options, $option);
        }

        $this->options[$option] = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        if ($this->title) {
            return $this->title;
        }

        if ($this->mode === static::MODE_CREATE) {
            return trans('messages.create');
        }

        if ($this->mode === static::MODE_EDIT) {
            return trans('messages.edit');
        }

        return '';
    }

    /**
     * Determine if form fields has files.
     *
     * @return bool
     */
    public function hasFile(): bool
    {
        // foreach ($this->fields() as $field) {
        //     if ($field instanceof Field\File || $field instanceof Field\MultipleFile) {
        //         return true;
        //     }
        // }

        if($this->form->hasFile()) {
            return true;
        }

        if($this->getTools()->hasTool('avatar')) {
            return true;
        }

        return false;
    }

    /**
     * Add field for store redirect url after update or store.
     *
     * @return void
     */
    protected function addRedirectUrlField()
    {
        $previous = URL::previous();

        if (!$previous || $previous === URL::current()) {
            return;
        }

        if (Str::contains($previous, url($this->getResource()))) {
            $this->addHiddenField((new Hidden(static::PREVIOUS_URL_KEY))->value($previous));
        }
    }

    /**
     * Open up a new HTML form.
     *
     * @param array $options
     *
     * @return string
     */
    public function open($options = []): string
    {
        $attributes = [];
        $validated_class = '';

        if(Arr::get($options, 'validated', false)) {
            $validated_class = 'was-validated';
        }

        if ($this->isMode(self::MODE_EDIT)) {
            $this->addHiddenField((new Hidden('_method'))->value('PUT'));
        }

        $this->addRedirectUrlField();

        $attributes['action'] = $this->getAction();
        $attributes['method'] = Arr::get($options, 'method', 'post');
        $attributes['class'] = implode(' ', ['form-horizontal', $this->formClass, $validated_class]);
        $attributes['accept-charset'] = 'UTF-8';

        if ($this->hasFile()) {
            $attributes['enctype'] = 'multipart/form-data';
        }

        $html = [];
        foreach ($attributes as $name => $value) {
            $html[] = "$name=\"$value\"";
        }

        return '<form '.implode(' ', $html).'>';
    }

    /**
     * Close the current form.
     *
     * @return string
     */
    public function close(): string
    {
        $this->form = null;
        $this->fields = null;

        return '</form>';
    }

    /**
     * Remove reserved fields like `id` `created_at` `updated_at` in form fields.
     *
     * @return void
     */
    protected function removeReservedFields()
    {
        if (!$this->isCreating()) {
            return;
        }

        $reservedColumns = [
            $this->form->model()->getCreatedAtColumn(),
            $this->form->model()->getUpdatedAtColumn(),
        ];

        if ($this->form->model()->incrementing) {
            $reservedColumns[] = $this->form->model()->getKeyName();
        }

        $this->form->getLayout()->removeReservedFields($reservedColumns);

        $this->fields = $this->fields()->reject(function (BaseField $field) use ($reservedColumns) {
            return in_array($field->column(), $reservedColumns, true);
        });
    }

    protected function getFormScripts()
    {
        $preview_data = [];
        $fields = collect()->merge($this->fields())->merge($this->tools->all());

        foreach($fields as $field) {
            $column = $field->column();
            if(is_array($column)) $column = $column['name'];

            if(is_array($field->getId())) {
                $preview_data[$column] = $field->getId()['name'];
            } else {
                $preview_data[$column] = $field->getId();
            }
        }

        $preview_data = json_encode(array_unique($preview_data));
        $model = get_class($this->form->model());
        $model = explode("\\", $model);
        $model = $model[count($model) - 1];
        $preview_url = route('admin.preview_url');
        $token = csrf_token();

        return <<<JS
        <script>
            $(window).bind('beforeunload', function (event) {
                return true;
            });

            $('form').bind('submit', function () {
                $(window).unbind('beforeunload');
            });

                $('#preview-link').on('click', function() {
                const fields = {$preview_data},
                      model = "$model";
                let data = {};

                Object.keys(fields).forEach(function(key) {
                    data[key] = $('[name="' + key + '"]:not(:disabled)').val();
                });

                $.post("$preview_url", { data, model, _token: '$token' }, function(){
                    window.open("$preview_url");
                });
            });
        </script>
        JS;
    }

    /**
     * Render form.
     *
     * @return string
     */
    public function render(): string
    {
        AdminAsset::push('scripts', $this->getFormScripts());

        $this->removeReservedFields();

        $tabs = $this->form->setTab();
        $data = [
            'form'   => $this,
            'tabs'   => $tabs,
            'layout' => $this->form->getLayout(),
            'tools'  => $this->getTools()
        ];

        return view($this->view, $data)->render();
    }

    /**
     * Render form header tools.
     *
     * @return string
     */
    public function renderTools(): string
    {
        return $this->tools->render();
    }

    public function getForm()
    {
        return $this->form;
    }

    public function canPreview()
    {
        return $this->form->canPreview();
    }
}
