<?php
namespace App\Form;

use App\Form\Form;
use Illuminate\Support\Collection;
use App\Form\Fields\BaseField;
use App\Form\Layout\Column;

class Layout
{
    /**
     * @var Collection
     */
    protected $columns;

    /**
     * @var Column
     */
    protected $current;

    /**
     * @var Form
     */
    protected $parent;

    /**
     * @var array
     */
    protected $custom_content;

    /**
     * Layout constructor.
     *
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->parent = $form;

        $this->current = new Column();

        $this->columns = new Collection();
    }

    /**
     * Add a filter to layout column.
     *
     * @param BaseField $field
     */
    public function addField(BaseField $field)
    {
        $this->current->add($field);
    }

    /**
     * Add a new column in layout.
     *
     * @param int      $width
     * @param \Closure $closure
     */
    public function column($width, \Closure $closure)
    {
        if ($this->columns->isEmpty()) {
            $column = $this->current;

            $column->setWidth($width);
        } else {
            $column = new Column($width);

            $this->current = $column;
        }

        $this->columns->push($column);

        $closure($this->parent);
    }

    /**
     * Get all columns in filter layout.
     *
     * @return Collection
     */
    public function columns()
    {
        if ($this->columns->isEmpty()) {
            $this->columns->push($this->current);
        }

        return $this->columns;
    }

    /**
     * Remove reserved fields from form layout.
     *
     * @param array $fields
     */
    public function removeReservedFields(array $fields)
    {
        if (empty($fields)) {
            return;
        }

        foreach ($this->columns() as &$column) {
            $column->removeFields($fields);
        }
    }

    /**
     * Get custom content
     * @return array 
     */
    public function customContent()
    {
        return $this->custom_content;
    }

    /**
     * Set custom content
     * @return array 
     */
    public function setCustomContent(string $content)
    {
        $this->custom_content = $content;

        return $this;
    }
}