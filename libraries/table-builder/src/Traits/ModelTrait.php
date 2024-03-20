<?php
namespace OCMS\TableBuilder\Traits;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use OCMS\TableBuilder\Row;

trait ModelTrait
{
    public Collection $items;
    public $page;
    public $last_page;
    public $total;
    public $first_item = 0;
    public $last_item  = 0;

    protected $per_page = 10;
    private ?Closure $query = null;
    private $relations;

    /**
     * 
     * @return array 
     */
    protected function rows()
    {
        return [

        ];
    }

    /**
     * Add relations to query
     * 
     * @param mixed $relations 
     * @return $this 
     */
    public function addRelations($relations)
    {
        $this->relations = $relations;

        return $this;
    }

    /**
     * Apply query
     * 
     * @param mixed $query 
     * @return $this 
     */
    public function applyQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Init model
     * 
     * @return $this 
     */
    public function initModel(Request $request)
    {
        $data = $this->model->query();

        if($this->relations) {
            $data->with($this->relations);    
        }

        if($this->query) {
            $data->where(fn($sub_query) => ($this->query)($data, $request));
        }
        
        $data             = $data->paginate($request->per_page ?: $this->per_page);
        $this->per_page   = $data->perPage();
        $this->page       = $data->currentPage();
        $this->last_page  = $data->lastPage();
        $this->total      = $data->total();
        $this->first_item = $data->firstItem();
        $this->last_item  = $data->lastItem();
        $this->items      = collect([]);
        
        if(count($data->items()) > 0) {
            $resolves = [];

            foreach($this->columns as $column) {
                if($column->display instanceof Closure) {
                    $resolves[$column->field] = $column->display;
                }
            }
            
            $this->items = $data->getCollection()->map(fn($row) => new Row($row, $resolves));
        }

        return $this;
    }

    /**
     * Convert items to array
     * 
     * @return array 
     */
    public function getArrayItems()
    {
        $result = [];
        $items  = $this->items->toArray();
        
        foreach($items as $item) {
            $row = $item->toArray();
            
            foreach($this->columns as $column) {
                if($column->display) {
                    $field = $column->field;
                    $row[$field] = $item->$field;
                }
            }

            $result[] = $row;
        }

        return $result;
    }
}