<?php
namespace OCMS\TableBuilder\Traits;

trait HeaderTrait
{
    protected bool $show_header          = true;
    protected bool $show_search          = true;
    protected bool $show_create          = true;
    protected bool $show_per_page_select = true;

    protected string $header_title = "";
    public string $create_url   = '#';

    public function hasHeader()
    {
        return $this->show_header;
    }

    public function hasSearch()
    {
        return $this->show_search;
    }

    public function hasCreate()
    {
        return $this->show_create;
    }

    public function hasPerPageSelect()
    {
        return $this->show_per_page_select;
    }

    public function headerTitle()
    {
        return __($this->header_title);
    }
}