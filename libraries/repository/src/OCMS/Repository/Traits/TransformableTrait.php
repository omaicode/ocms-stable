<?php

namespace OCMS\Repository\Traits;

/**
 * Class TransformableTrait
 * @package OCMS\Repository\Traits
 * @author Anderson Andrade <contato@andersonandra.de>
 */
trait TransformableTrait
{
    /**
     * @return array
     */
    public function transform()
    {
        return $this->toArray();
    }
}
