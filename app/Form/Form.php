<?php
namespace App\Form;

use Closure;
use Exception;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use App\Form\Fields\BaseField;
use App\Form\Fields\Nullable;
use App\Form\Layout\Row;
use App\Form\Traits\HandleCascadeFields;
use App\Form\Traits\HasFields;
use App\Form\Traits\HasHooks;
use Spatie\EloquentSortable\Sortable;

class Form implements Renderable
{
    use HasFields;
    use HasHooks;
    use HandleCascadeFields;

    /**
     * Remove flag in `has many` form.
     */
    const REMOVE_FLAG_NAME = '_remove_';

    /**
     * Model
     *
     * @var mixed
     */
    protected $model;

    /**
     * @var \Illuminate\Validation\Validator
     */
    protected $validator;

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * Data for save to current model from input.
     *
     * @var array
     */
    protected $updates = [];

    /**
     * Data for save to model's relations from input.
     *
     * @var array
     */
    protected $relations = [];

    /**
     * Input data.
     *
     * @var array
     */
    protected $inputs = [];

    /**
     * @var Layout
     */
    protected $layout;

    /**
     * Ignored saving fields.
     *
     * @var array
     */
    protected $ignored = [];

    /**
     * Collected field assets.
     *
     * @var array
     */
    protected static $collectedAssets = [];

    /**
     * Field rows in form.
     *
     * @var array
     */
    public $rows = [];

    /**
     * Set redirect url after submit form
     *
     * @var string
     */
    protected $redirectUrl;

    /**
     *
     * @var array
     */
    protected array $overrides = [];

    /**
     * @var bool
     */
    protected bool $previewable = false;

    /**
     * @var bool
     */
    protected bool $hasFile = false;

    public function __construct($model, ?Closure $callback = null)
    {
        $this->builder = new Builder($this);

        $this->model = $model;
        $this->initLayout();

        if ($callback instanceof Closure) {
            $callback($this);
        }

        $this->callInitCallbacks();
    }

    /**
     *
     * @return mixed
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * @return Builder
     */
    public function builder(): Builder
    {
        return $this->builder;
    }

    /**
     * Generate a edit form.
     *
     * @param $id
     *
     * @return $this
     */
    public function edit($id): self
    {
        $this->builder->setMode(Builder::MODE_EDIT);
        $this->builder->setResourceId($id);

        $this->setRelationFieldSnakeAttributes();

        $this->setFieldValue($id);

        return $this;
    }

    /**
     * Store a new record.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $data = \request()->all();

        // Handle validation errors.
        if ($validationMessages = $this->validationMessages($data)) {
            return $this->responseValidationError($validationMessages);
        }

        if (($response = $this->prepare($data)) instanceof Response) {
            return $response;
        }

        DB::transaction(function () {
            $inserts = $this->prepareInsert($this->updates);
            foreach ($inserts as $column => $value) {
                $this->model->setAttribute($column, $value);
            }

            $this->model->save();
            $this->updateRelation($this->relations);
        });

        if (($response = $this->callSaved()) instanceof Response) {
            return $response;
        }

        if ($response = $this->ajaxResponse(trans('admin.save_succeeded'))) {
            return $response;
        }

        $this->onSaved();
        return $this->redirectAfterStore();
    }

    /**
     * @param MessageBag $message
     *
     * @return $this|\Illuminate\Http\JsonResponse
     */
    protected function responseValidationError(MessageBag $message)
    {
        if (\request()->ajax() && !\request()->pjax()) {
            return response()->json([
                'status'     => false,
                'validation' => $message,
                'message'    => $message->first(),
            ]);
        }

        return back()->withInput()->withErrors($message);
    }

    /**
     * Get ajax response.
     *
     * @param string $message
     *
     * @return bool|\Illuminate\Http\JsonResponse
     */
    protected function ajaxResponse($message)
    {
        $request = \request();

        // ajax but not pjax
        if ($request->ajax() && !$request->pjax()) {
            return response()->json([
                'status'    => true,
                'message'   => $message,
                'display'   => $this->applyFieldDisplay(),
            ]);
        }

        return false;
    }

    /**
     * @return array
     */
    protected function applyFieldDisplay()
    {
        $editable = [];

        /** @var Field $field */
        foreach ($this->fields() as $field) {
            if (!\request()->has($field->column())) {
                continue;
            }

            $newValue = $this->model->fresh()->getAttribute($field->column());

            if ($newValue instanceof Arrayable) {
                $newValue = $newValue->toArray();
            }

            if ($field instanceof BelongsTo || $field instanceof BelongsToMany) {
                $selectable = $field->getSelectable();

                if (method_exists($selectable, 'display')) {
                    $display = $selectable::display();

                    $editable[$field->column()] = $display->call($this->model, $newValue);
                }
            }
        }

        return $editable;
    }

    /**
     * Prepare input data for insert or update.
     *
     * @param array $data
     *
     * @return mixed
     */
    protected function prepare($data = [])
    {
        if (($response = $this->callSubmitted()) instanceof Response) {
            return $response;
        }

        $this->inputs = array_merge($this->removeIgnoredFields($data), $this->inputs);

        if (($response = $this->callSaving()) instanceof Response) {
            return $response;
        }

        $this->relations = $this->getRelationInputs($this->inputs);
        $this->updates = Arr::except($this->inputs, array_keys($this->relations));
    }

    /**
     * Remove ignored fields from input.
     *
     * @param array $input
     *
     * @return array
     */
    protected function removeIgnoredFields($input): array
    {
        Arr::forget($input, $this->ignored);

        return $input;
    }

    /**
     * Get inputs for relations.
     *
     * @param array $inputs
     *
     * @return array
     */
    protected function getRelationInputs($inputs = []): array
    {
        $relations = [];

        foreach ($inputs as $column => $value) {
            if ((method_exists($this->model, $column) ||
                method_exists($this->model, $column = Str::camel($column))) &&
                !method_exists(Model::class, $column)
            ) {
                $relation = call_user_func([$this->model, $column]);

                if ($relation instanceof Relation) {
                    $relations[$column] = $value;
                }
            }
        }

        return $relations;
    }


    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $this->builder->setMode(Builder::MODE_DELETE);
        $this->builder->setResourceId($id);
        $this->setFieldValue($id);
        $this->model()->delete();
        $this->onDeleted();
        return $this->redirectAfterUpdate("");
    }

    /**
     * Handle update.
     *
     * @param int  $id
     * @param null $data
     *
     * @return bool|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|mixed|null|Response
     */
    public function update($id, $data = null)
    {
        $data = ($data) ?: request()->all();
        $isEditable = $this->isEditable($data);

        if (($data = $this->handleColumnUpdates($id, $data)) instanceof Response) {
            return $data;
        }

        /* @var Model $this ->model */
        $builder = $this->model();

        if ($this->isSoftDeletes) {
            $builder = $builder->withTrashed();
        }

        $this->model = $builder->with($this->getRelations())->findOrFail($id);

        $this->setFieldOriginalValue();

        // Handle validation errors.
        if ($validationMessages = $this->validationMessages($data)) {
            if (!$isEditable) {
                return back()->withInput()->withErrors($validationMessages);
            }

            return response()->json(['errors' => Arr::dot($validationMessages->getMessages())], 422);
        }

        if (($response = $this->prepare($data)) instanceof Response) {
            return $response;
        }

        DB::transaction(function () {
            $updates = $this->prepareUpdate($this->updates);
            foreach ($updates as $column => $value) {
                /* @var Model $this ->model */
                $this->model->setAttribute($column, $value);
            }

            $this->model->save();

            $this->updateRelation($this->relations);
        });

        if (($result = $this->callSaved()) instanceof Response) {
            return $result;
        }

        if ($response = $this->ajaxResponse(trans('admin.update_succeeded'))) {
            return $response;
        }

        $this->onUpdated();
        return $this->redirectAfterUpdate($id);
    }

    /**
     * Get RedirectResponse after store.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectAfterStore()
    {
        $resourcesPath = $this->resource(0);

        $key = $this->model->getKey();

        return $this->redirectAfterSaving($resourcesPath, $key);
    }

    /**
     * Get RedirectResponse after update.
     *
     * @param mixed $key
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectAfterUpdate($key)
    {
        $resourcesPath = $this->resource(-1);
        return $this->redirectAfterSaving($resourcesPath, $key);
    }

    /**
     * Get RedirectResponse after data saving.
     *
     * @param string $resourcesPath
     * @param string $key
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function redirectAfterSaving($resourcesPath, $key)
    {
        if(!$this->redirectUrl) {
            if (request('after-save') == 1) {
                // continue editing
                $url = rtrim($resourcesPath, '/')."/{$key}/edit";
            } elseif (request('after-save') == 2) {
                // continue creating
                $url = rtrim($resourcesPath, '/').'/create';
            } elseif (request('after-save') == 3) {
                // view resource
                $url = rtrim($resourcesPath, '/')."/{$key}";
            } else {
                $url = request(Builder::PREVIOUS_URL_KEY) ?: $resourcesPath;
            }
        } else {
            $url = $this->redirectUrl;
        }

        return redirect($url);
    }

    /**
     * Check if request is from editable.
     *
     * @param array $input
     *
     * @return bool
     */
    protected function isEditable(array $input = []): bool
    {
        return array_key_exists('_editable', $input) || array_key_exists('_edit_inline', $input);
    }

    /**
     * Handle updates for single column.
     *
     * @param int   $id
     * @param array $data
     *
     * @return array|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|Response
     */
    protected function handleColumnUpdates($id, $data)
    {
        $data = $this->handleEditable($data);

        $data = $this->handleFileDelete($data);

        $data = $this->handleFileSort($data);

        if ($this->handleOrderable($id, $data)) {
            return response([
                'status'  => true,
                'message' => trans('admin.update_succeeded'),
            ]);
        }

        return $data;
    }

    /**
     * Handle editable update.
     *
     * @param array $input
     *
     * @return array
     */
    protected function handleEditable(array $input = []): array
    {
        if (array_key_exists('_editable', $input)) {
            $name = $input['name'];
            $value = $input['value'];

            Arr::forget($input, ['pk', 'value', 'name']);
            Arr::set($input, $name, $value);
        }

        return $input;
    }

    /**
     * @param array $input
     *
     * @return array
     */
    protected function handleFileDelete(array $input = []): array
    {
        if (array_key_exists(BaseField::FILE_DELETE_FLAG, $input)) {
            $input[BaseField::FILE_DELETE_FLAG] = $input['key'];
            unset($input['key']);
        }

        request()->replace($input);

        return $input;
    }

    /**
     * Handle orderable update.
     *
     * @param int   $id
     * @param array $input
     *
     * @return bool
     */
    protected function handleOrderable($id, array $input = [])
    {
        if (array_key_exists('_orderable', $input)) {
            $model = $this->model->find($id);

            if ($model instanceof Sortable) {
                $input['_orderable'] == 1 ? $model->moveOrderUp() : $model->moveOrderDown();

                return true;
            }
        }

        return false;
    }

    /**
     * @param array $input
     *
     * @return array
     */
    protected function handleFileSort(array $input = []): array
    {
        if (!array_key_exists(BaseField::FILE_SORT_FLAG, $input)) {
            return $input;
        }

        $sorts = array_filter($input[BaseField::FILE_SORT_FLAG]);

        if (empty($sorts)) {
            return $input;
        }

        foreach ($sorts as $column => $order) {
            $input[$column] = $order;
        }

        request()->replace($input);

        return $input;
    }

    /**
     * Update relation data.
     *
     * @param array $relationsData
     *
     * @return void
     */
    protected function updateRelation($relationsData)
    {
        foreach ($relationsData as $name => $values) {
            if (!method_exists($this->model, $name)) {
                continue;
            }

            $relation = $this->model->$name();

            $oneToOneRelation = $relation instanceof HasOne
                || $relation instanceof MorphOne
                || $relation instanceof BelongsTo;

            $prepared = $this->prepareUpdate([$name => $values], $oneToOneRelation);

            if (empty($prepared)) {
                continue;
            }

            switch (true) {
                case $relation instanceof BelongsToMany:
                case $relation instanceof MorphToMany:
                    if (isset($prepared[$name])) {
                        $relation->sync($prepared[$name]);
                    }
                    break;
                case $relation instanceof HasOne:
                case $relation instanceof MorphOne:
                    $related = $this->model->getRelationValue($name) ?: $relation->getRelated();

                    foreach ($prepared[$name] as $column => $value) {
                        $related->setAttribute($column, $value);
                    }

                    // save child
                    $relation->save($related);
                    break;
                case $relation instanceof BelongsTo:
                case $relation instanceof MorphTo:
                    $related = $this->model->getRelationValue($name) ?: $relation->getRelated();

                    foreach ($prepared[$name] as $column => $value) {
                        $related->setAttribute($column, $value);
                    }

                    // save parent
                    $related->save();

                    // save child (self)
                    $relation->associate($related)->save();
                    break;
                case $relation instanceof HasMany:
                case $relation instanceof MorphMany:
                    /** @var HasOneOrMany $relation */
                    $relation = $this->model->$name();

                    $data = $prepared[$name];
                    $first = Arr::first($data);

                    if (is_array($first)) { //relation updated via HasMany field
                        foreach ($data as $related) {
                            /** @var HasOneOrMany $relation */
                            $relation = $this->model->$name();

                            $keyName = $relation->getRelated()->getKeyName();

                            /** @var Model $child */
                            $child = $relation->findOrNew(Arr::get($related, $keyName));

                            if (Arr::get($related, static::REMOVE_FLAG_NAME) == 1) {
                                $child->delete();
                                continue;
                            }

                            Arr::forget($related, static::REMOVE_FLAG_NAME);

                            $child->fill($related);

                            $child->save();
                        }
                    } else { //relation updated via MultipleSelect field
                        $foreignKeyName = $relation->getForeignKeyName();
                        $localKeyName = $relation->getLocalKeyName();

                        foreach ($relation->get() as $child) {
                            if (($ind = array_search($child->getKey(), $data)) !== false) {
                                unset($data[$ind]);
                            } else {
                                $child->$foreignKeyName = null;
                                $child->save();
                            }
                        }

                        foreach ($data as $id) {
                            $child = $relation->getRelated()->find($id);
                            $child->$foreignKeyName = $this->model->$localKeyName;
                            $child->save();
                        }
                    }
                    break;
            }
        }
    }

    /**
     * Prepare input data for update.
     *
     * @param array $updates
     * @param bool  $oneToOneRelation If column is one-to-one relation.
     *
     * @return array
     */
    protected function prepareUpdate(array $updates, $oneToOneRelation = false): array
    {
        $prepared = [];

        /** @var Field $field */
        foreach ($this->fields() as $field) {
            $columns = $field->column();

            // If column not in input array data, then continue.
            if (!Arr::has($updates, $columns)) {
                continue;
            }

            if ($this->isInvalidColumn($columns, $oneToOneRelation || $field->isJsonType)) {
                continue;
            }

            $value = $this->getDataByColumn($updates, $columns);

            $value = $field->prepare($value);

            if (is_array($columns)) {
                foreach ($columns as $name => $column) {
                    Arr::set($prepared, $column, $value[$name]);
                }
            } elseif (is_string($columns)) {
                Arr::set($prepared, $columns, $value);
            }
        }

        foreach($this->builder->getTools()->all() as $tool) {
            if($tool instanceof BaseField) {
                $columns = $tool->column();

                if (!Arr::has($updates, $columns)) {
                    continue;
                }

                $value = $this->getDataByColumn($updates, $columns);

                $value = $field->prepare($value);

                if (is_array($columns)) {
                    foreach ($columns as $name => $column) {
                        Arr::set($prepared, $column, $value[$name]);
                    }
                } elseif (is_string($columns)) {
                    Arr::set($prepared, $columns, $value);
                }
            }
        }

        return $prepared;
    }

    /**
     * @param string|array $columns
     * @param bool         $containsDot
     *
     * @return bool
     */
    protected function isInvalidColumn($columns, $containsDot = false): bool
    {
        foreach ((array) $columns as $column) {
            if ((!$containsDot && Str::contains($column, '.')) ||
                ($containsDot && !Str::contains($column, '.'))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Prepare input data for insert.
     *
     * @param $inserts
     *
     * @return array
     */
    protected function prepareInsert($inserts): array
    {
        if ($this->isHasOneRelation($inserts)) {
            $inserts = Arr::dot($inserts);
        }
        foreach ($inserts as $column => $value) {
            if (($field = $this->getFieldByColumn($column)) !== null) {
                $inserts[$column] = $field->prepare($value);
            } else if(($tool = $this->getToolsByColumn($column)) !== null) {
                $inserts[$column] = $tool->prepare($value);
            } else if(in_array($column, $this->overrides)) {
                $inserts[$column] = $value;
            } else {
                unset($inserts[$column]);
            }
        }

        $prepared = [];

        foreach ($inserts as $key => $value) {
            Arr::set($prepared, $key, $value);
        }

        return $prepared;
    }

    /**
     * Is input data is has-one relation.
     *
     * @param array $inserts
     *
     * @return bool
     */
    protected function isHasOneRelation($inserts): bool
    {
        $first = current($inserts);

        if (!is_array($first)) {
            return false;
        }

        if (is_array(current($first))) {
            return false;
        }

        return Arr::isAssoc($first);
    }

    /**
     * Ignore fields to save.
     *
     * @param string|array $fields
     *
     * @return $this
     */
    public function ignore($fields): self
    {
        $this->ignored = array_merge($this->ignored, (array) $fields);

        return $this;
    }

    /**
     * @param array        $data
     * @param string|array $columns
     *
     * @return array|mixed
     */
    protected function getDataByColumn($data, $columns)
    {
        if (is_string($columns)) {
            return Arr::get($data, $columns);
        }

        if (is_array($columns)) {
            $value = [];
            foreach ($columns as $name => $column) {
                if (!Arr::has($data, $column)) {
                    continue;
                }
                $value[$name] = Arr::get($data, $column);
            }

            return $value;
        }
    }

    /**
     * Find field object by column.
     *
     * @param $column
     *
     * @return mixed
     */
    protected function getFieldByColumn($column)
    {
        return $this->fields()->first(
            function (BaseField $field) use ($column) {
                if (is_array($field->column())) {
                    return in_array($column, $field->column());
                }

                return $field->column() == $column;
            }
        );
    }

    /**
     * Find field object by column.
     *
     * @param $column
     *
     * @return mixed
     */
    protected function getToolsByColumn($column)
    {
        return $this->builder()->getTools()->all()->first(
            function ($tool) use ($column) {
                if($tool instanceof BaseField) {
                    return $tool->column() == $column;
                }

                return false;
            }
        );
    }

    /**
     * Set original data for each field.
     *
     * @return void
     */
    protected function setFieldOriginalValue()
    {
        $values = $this->model->toArray();

        $this->fields()->each(function (BaseField $field) use ($values) {
            $field->setOriginal($values);
        });
    }

    /**
     * Determine relational column needs to be snaked.
     *
     * @return void
     */
    protected function setRelationFieldSnakeAttributes()
    {
        $relations = $this->getRelations();

        $this->fields()->each(function (BaseField $field) use ($relations) {
            if ($field->getSnakeAttributes()) {
                return;
            }

            $column = $field->column();

            $column = is_array($column) ? head($column) : $column;

            list($relation) = explode('.', $column);

            if (!in_array($relation, $relations)) {
                return;
            }

            $field->setSnakeAttributes($this->model::$snakeAttributes);
        });
    }

    /**
     * Set all fields value in form.
     *
     * @param $id
     *
     * @return void
     */
    protected function setFieldValue($id)
    {
        $relations = $this->getRelations();

        $builder = $this->model();

        if ($this->isSoftDeletes) {
            $builder = $builder->withTrashed();
        }

        $this->model = $builder->with($relations)->findOrFail($id);

        $this->callEditing();

        $data = $this->model->toArray();

        $this->fields()->each(function (BaseField $field) use ($data) {
            if (!in_array($field->column(), $this->ignored, true)) {
                $field->fill($data);
            }
        });
    }

    /**
     * Get validation messages.
     *
     * @param array $input
     *
     * @return MessageBag|bool
     */
    public function validationMessages($input)
    {
        $failedValidators = [];

        /** @var Field $field */
        foreach ($this->fields() as $field) {
            if (!$validator = $field->getValidator($input)) {
                continue;
            }

            if (($validator instanceof Validator) && !$validator->passes()) {
                $failedValidators[] = $validator;
            }
        }

        /** @var Tools $tool */
        foreach($this->builder->getTools()->all() as $tool) {
            if($tool instanceof BaseField) {
                if (!$validator = $tool->getValidator($input)) {
                    continue;
                }

                if (($validator instanceof Validator) && !$validator->passes()) {
                    $failedValidators[] = $validator;
                }
            }
        }

        $message = $this->mergeValidationMessages($failedValidators);
        return $message->any() ? $message : false;
    }

    /**
     * Merge validation messages from input validators.
     *
     * @param \Illuminate\Validation\Validator[] $validators
     *
     * @return MessageBag
     */
    protected function mergeValidationMessages($validators): MessageBag
    {
        $messageBag = new MessageBag();

        foreach ($validators as $validator) {
            $messageBag = $messageBag->merge($validator->messages());
        }

        return $messageBag;
    }

    /**
     * Get all relations of model from callable.
     *
     * @return array
     */
    public function getRelations(): array
    {
        $relations = $columns = [];

        /** @var Field $field */
        foreach ($this->fields() as $field) {
            $columns[] = $field->column();
        }

        foreach (Arr::flatten($columns) as $column) {
            if (Str::contains($column, '.')) {
                list($relation) = explode('.', $column);

                if (method_exists($this->model, $relation) &&
                    !method_exists(Model::class, $relation) &&
                    $this->model->$relation() instanceof Relation
                ) {
                    $relations[] = $relation;
                }
            } elseif (method_exists($this->model, $column) &&
                !method_exists(Model::class, $column)
            ) {
                $relations[] = $column;
            }
        }

        return array_unique($relations);
    }

    /**
     * Add a row in form.
     *
     * @param Closure $callback
     *
     * @return $this
     */
    public function row(Closure $callback): self
    {
        $this->rows[] = new Row($callback, $this);

        return $this;
    }

    /**
     * Tools setting for form.
     *
     * @param Closure $callback
     */
    public function tools(Closure $callback)
    {
        $callback->call($this, $this->builder->getTools());
    }

    /**
     * Indicates if current form page is creating.
     *
     * @return bool
     */
    public function isCreating(): bool
    {
        return Str::endsWith(\request()->route()->getName(), ['.create', '.store']);
    }

    /**
     * Indicates if current form page is editing.
     *
     * @return bool
     */
    public function isEditing(): bool
    {
        return Str::endsWith(\request()->route()->getName(), ['.edit', '.update']);
    }

    /**
     * Disable form submit.
     *
     * @param bool $disable
     *
     * @return $this
     *
     * @deprecated
     */
    public function disableSubmit(bool $disable = true): self
    {
        $this->builder()->getFooter()->disableSubmit($disable);

        return $this;
    }

    /**
     * Disable form reset.
     *
     * @param bool $disable
     *
     * @return $this
     *
     * @deprecated
     */
    public function disableReset(bool $disable = true): self
    {
        $this->builder()->getFooter()->disableReset($disable);

        return $this;
    }

    /**
     * Disable View Checkbox on footer.
     *
     * @param bool $disable
     *
     * @return $this
     */
    public function disableViewCheck(bool $disable = true): self
    {
        $this->builder()->getFooter()->disableViewCheck($disable);

        return $this;
    }

    /**
     * Disable Editing Checkbox on footer.
     *
     * @param bool $disable
     *
     * @return $this
     */
    public function disableEditingCheck(bool $disable = true): self
    {
        $this->builder()->getFooter()->disableEditingCheck($disable);

        return $this;
    }

    /**
     * Disable Creating Checkbox on footer.
     *
     * @param bool $disable
     *
     * @return $this
     */
    public function disableCreatingCheck(bool $disable = true): self
    {
        $this->builder()->getFooter()->disableCreatingCheck($disable);

        return $this;
    }

    /**
     * Set action for form.
     *
     * @param string $action
     *
     * @return $this
     */
    public function setAction($action): self
    {
        $this->builder()->setAction($action);

        return $this;
    }

    /**
     * Get current resource route url.
     *
     * @param int $slice
     *
     * @return string
     */
    public function resource($slice = -2): string
    {
        $segments = explode('/', trim(\request()->getUri(), '/'));

        if ($slice !== 0) {
            $segments = array_slice($segments, 0, $slice);
        }

        return implode('/', $segments);
    }

    /**
     * Get or set input data.
     *
     * @param string $key
     * @param null   $value
     *
     * @return array|mixed
     */
    public function input($key, $value = null)
    {
        if ($value === null) {
            return Arr::get($this->inputs, $key);
        }

        return Arr::set($this->inputs, $key, $value);
    }

    /**
     * Add a new layout column.
     *
     * @param int      $width
     * @param \Closure $closure
     *
     * @return $this
     */
    public function column($width, \Closure $closure): self
    {
        $width = $width < 1 ? round(12 * $width) : $width;

        $this->layout->column($width, $closure);

        return $this;
    }

    /**
     * Initialize filter layout.
     */
    protected function initLayout()
    {
        $this->layout = new Layout($this);
    }

    /**
     * On saved event
     *
     * @return void
     */
    protected function onSaved()
    {
        \request()->session()->flash('toast_success', __('messages.created'));
    }

    /**
     * On updated event
     * @return void
     */
    protected function onUpdated()
    {
        \request()->session()->flash('toast_success', __('messages.updated'));
    }

    /**
     * On updated event
     * @return void
     */
    protected function onDeleted()
    {
        \request()->session()->flash('toast_success', __('messages.deleted'));
    }

    public function overrides(...$args)
    {
        foreach($args as $val) {
            $this->overrides[] = $val;
        }

        return $this;
    }


    /**
     * @return Layout
     */
    public function getLayout(): Layout
    {
        return $this->layout;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function fields()
    {
        return $this->builder()->fields();
    }

    /**
     * @param Field $field
     *
     * @return $this
     */
    public function pushField(BaseField $field): self
    {
        $field->setForm($this);

        $this->fields()->push($field);
        $this->layout->addField($field);

        return $this;
    }

    /**
     * Render the form contents.
     *
     * @return string
     */
    public function render()
    {
        try {
            return $this->builder->render();
        } catch (\Exception $e) {
            throw new Exception($e);
        }
    }

    public function title(string $title)
    {
        $this->builder->setTitle($title);

        return $this;
    }

    public function redirectUrl(string $url)
    {
        $this->redirectUrl = $url;

        return $this;
    }

    /**
     * @param bool $preview
     * @return $this
     */
    public function preview(bool $preview)
    {
        $this->previewable = $preview;
        return $this;
    }

    /**
     * @return bool
     */
    public function canPreview()
    {
        return $this->previewable;
    }

    /**
     * @return $this
     */
    public function hasFile()
    {
        return $this->hasFile;
    }

    /**
     * @return $this
     */
    public function setHasFile(bool $has_file = true)
    {
        $this->hasFile = $has_file;
        return $this;
    }

    /**
     * Getter.
     *
     * @param string $name
     *
     * @return array|mixed
     */
    public function __get($name)
    {
        return $this->input($name);
    }

    /**
     * Setter.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return array
     */
    public function __set($name, $value)
    {
        return Arr::set($this->inputs, $name, $value);
    }

    /**
     * __isset.
     *
     * @param string $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->inputs[$name]);
    }

    /**
     * Generate a Field object and add to form builder if Field exists.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return Field
     */
    public function __call($method, $arguments)
    {
        if ($className = static::findFieldClass($method)) {
            $column = Arr::get($arguments, 0, ''); //[0];
            $element = new $className($column, array_slice($arguments, 1));
            $this->pushField($element);

            return $element;
        }

        return new Nullable;
    }
}
