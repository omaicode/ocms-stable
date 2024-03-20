<?php
namespace App\Supports;

use AdminAsset;
use OCMS\TableBuilder\Builder;

class TableBuilder extends Builder
{
    public function __construct(array $options = [])
    {
        parent::__construct($options);
    }

    protected function beforeRender()
    {
        AdminAsset::addByGroups(config('table_builder.assets', []));        
    }
}