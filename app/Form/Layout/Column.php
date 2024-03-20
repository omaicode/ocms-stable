<?php
namespace App\Form\Layout;

use App\Form\Fields\BaseField;
use Illuminate\Support\Collection;

class Column
{
    /**
     * @var Collection
     */
    protected $fields;

    /**
     * @var int
     */
    protected $width;

    /**
     * Column constructor.
     *
     * @param int $width
     */
    public function __construct($width = 12)
    {
        $this->width = $width;
        $this->fields = new Collection();
    }

    /**
     * Add a filter to this column.
     *
     * @param Field $field
     */
    public function add(BaseField $field)
    {
        $this->fields->push($field);
    }

    /**
     * Remove fields from column.
     *
     * @param $fields
     */
    public function removeFields($fields)
    {
        $this->fields = $this->fields->reject(function (BaseField $field) use ($fields) {
            return in_array($field->column(), $fields);
        });
    }

    /**
     * Get all filters in this column.
     *
     * @return Collection
     */
    public function fields()
    {
        return $this->fields;
    }

    /**
     * Set column width.
     *
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * Get column width.
     *
     * @return int
     */
    public function width()
    {
        return $this->width;
    }
}