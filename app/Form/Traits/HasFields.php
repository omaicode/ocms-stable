<?php
namespace App\Form\Traits;

use App\Form\Fields\Checkbox;
use App\Form\Fields\Hidden;
use Illuminate\Support\Arr;
use App\Form\Fields\Display;
use App\Form\Fields\Ckeditor;
use App\Form\Fields\CodeMirror;
use App\Form\Fields\Password;
use App\Form\Fields\QuillEditor;
use App\Form\Fields\Select;
use App\Form\Fields\Slug;
use App\Form\Fields\Text;
use App\Form\Fields\Textarea;

/**
 * @method App\Form\Fields\Text        text($column, $label = '')
 * @method App\Form\Fields\Hidden      hidden($column)
 * @method App\Form\Fields\Slug        slug($column, $slug_name, $label = '')
 * @method App\Form\Fields\Textarea    textarea($column, $label = '')
 * @method App\Form\Fields\Select      select($column, $label = '')
 * @method App\Form\Fields\Password    password($column, $label = '')
 * @method App\Form\Fields\Display     display($column, $label = '')
 * @method App\Form\Fields\Ckeditor    ckeditor($column, $label = '')
 * @method App\Form\Fields\QuillEditor    quillEditor($column, $label = '')
 * @method App\Form\Fields\CodeMirror    codeMirror($column, $label = '')
 * @method App\Form\Fields\Checkbox    checkbox($column, $label = '')
 */
trait HasFields
{
    /**
     * Available fields.
     *
     * @var array
     */
    public static $availableFields = [
        'text'      => Text::class,
        'hidden'    => Hidden::class,
        'slug'      => Slug::class,
        'textarea'  => Textarea::class,
        'select'    => Select::class,
        'password'  => Password::class,
        'display'   => Display::class,
        'ckeditor'  => Ckeditor::class,
        'quillEditor' => QuillEditor::class,
        'codeMirror' => CodeMirror::class,
        'checkbox' => Checkbox::class
    ];

    /**
     * Form field alias.
     *
     * @var array
     */
    public static $fieldAlias = [];

    /**
     * Register custom field.
     *
     * @param string $abstract
     * @param string $class
     *
     * @return void
     */
    public static function extend($abstract, $class)
    {
        static::$availableFields[$abstract] = $class;
    }

    /**
     * Set form field alias.
     *
     * @param string $field
     * @param string $alias
     *
     * @return void
     */
    public static function alias($field, $alias)
    {
        static::$fieldAlias[$alias] = $field;
    }

    /**
     * Remove registered field.
     *
     * @param array|string $abstract
     */
    public static function forget($abstract)
    {
        Arr::forget(static::$availableFields, $abstract);
    }

    /**
     * Find field class.
     *
     * @param string $method
     *
     * @return bool|mixed
     */
    public static function findFieldClass($method)
    {
        // If alias exists.
        if (isset(static::$fieldAlias[$method])) {
            $method = static::$fieldAlias[$method];
        }

        $class = Arr::get(static::$availableFields, $method);

        if (class_exists($class)) {
            return $class;
        }

        return false;
    }

    /**
     * Collect assets required by registered field.
     *
     * @return array
     */
    public static function collectFieldAssets(): array
    {
        if (!empty(static::$collectedAssets)) {
            return static::$collectedAssets;
        }

        $css = collect();
        $js = collect();

        foreach (static::$availableFields as $field) {
            if (!method_exists($field, 'getAssets')) {
                continue;
            }

            $assets = call_user_func([$field, 'getAssets']);

            $css->push(Arr::get($assets, 'css'));
            $js->push(Arr::get($assets, 'js'));
        }

        return static::$collectedAssets = [
            'css' => $css->flatten()->unique()->filter()->toArray(),
            'js'  => $js->flatten()->unique()->filter()->toArray(),
        ];
    }
}
