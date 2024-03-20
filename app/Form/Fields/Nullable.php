<?php
namespace App\Form\Fields;

class Nullable extends BaseField
{
    public function __construct()
    {
    }

    public function __call($method, $parameters)
    {
        return $this;
    }
}