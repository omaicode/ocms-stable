<?php
namespace OCMS\TableBuilder\Traits;

trait FooterTrait
{
    protected bool $show_footer = true;

    public function hasFooter()
    {
        return $this->show_footer;
    }
}