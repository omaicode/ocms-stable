<?php
namespace OCMS\TableBuilder;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use OCMS\TableBuilder\Exceptions\TableModelException;
use OCMS\TableBuilder\Exceptions\TableViewException;
use OCMS\TableBuilder\Traits\ActionTrait;
use OCMS\TableBuilder\Traits\ColumnTrait;
use OCMS\TableBuilder\Traits\FooterTrait;
use OCMS\TableBuilder\Traits\HeaderTrait;
use OCMS\TableBuilder\Traits\ModelTrait;
use OCMS\TableBuilder\Traits\RequestTrait;

class Builder implements Htmlable
{
    use ColumnTrait;
    use HeaderTrait;
    use FooterTrait;
    use ModelTrait;
    use ActionTrait;
    use RequestTrait;

    protected $model;
    protected string $theme = 'bootstrap';
    protected string $view  = 'omc::table.index';
    protected bool $show_loading = true;
    
    public function __construct(array $options = [])
    {
        if(!($this->model instanceof Model) && !is_subclass_of($this->model, Model::class)) {
            throw new TableModelException(__('omc.errors.model', ['class' => get_class($this->model)]));
        }

        if(isset($options['view'])) {
            if(!View::exists($options['view'])) {
                throw new TableViewException(__('omc.errors.view', ['view' => $options['view']]));
            } else {
                $this->view = $options['view'];
            }
        }

        if(isset($options['theme']) && in_array($options['theme'], ['bootstrap'])) {
            $this->theme = $options['theme'];
        } else {
            $this->theme = config('table_builder.theme', 'bootstrap');
        }        

        $this->model   = app($this->model);
    }

    /**
     * Call before render
     * 
     * @return void 
     */
    protected function beforeRender()
    {
        
    }    

    public function render()
    {
        $request = request();

        $this->initColumns();
        $this->initActions();
        $this->initModel($request);
        $this->handleRequest($request);
        $this->beforeRender();

        return View::make($this->view, [
            'theme' => $this->theme,
            'table' => $this
        ]);
    }

    /**
     * Render table to HTML
     * 
     * @param array $variables 
     * @return string 
     */
    public function toHtml()
    {        
        return $this->render()->render();
    }

    /**
     * Convert table data to json
     * 
     * @return string 
     */
    public function getJsonTable()
    {
        return collect([
            'columns'     => $this->getArrayColumns(),
            'items'       => $this->getArrayItems(),
            'last_page'   => $this->last_page,
            'total'       => $this->total,
            'first_item'  => $this->first_item,
            'last_item'   => $this->last_item,
            'delete_url'  => $this->delete_url,
            'actions'     => $this->actions->toArray(),
            'queryParams' => [
                'page'     => $this->page,
                'per_page' => $this->per_page,
                'search'   => request()->get('search', ''),
                'handle'   => 'table-builder'
            ],
            'options' => [
                'show_actions'  => $this->showActions(),
                'total_columns' => $this->totalColumns()
            ],
        ])->toJson();
    }

    /**
     * Check show loading
     * @return bool 
     */
    public function hasLoading()
    {
        return $this->show_loading;
    }
}