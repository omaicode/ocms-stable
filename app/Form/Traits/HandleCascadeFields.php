<?php
namespace App\Form\Traits;

use App\Form\Fields\CascadeGroup;

trait HandleCascadeFields
{
    /**
     * @param array    $dependency
     * @param \Closure $closure
     */
    public function cascadeGroup(\Closure $closure, array $dependency)
    {
        $this->pushField($group = new CascadeGroup($dependency));

        call_user_func($closure, $this);

        $group->end();
    }
}