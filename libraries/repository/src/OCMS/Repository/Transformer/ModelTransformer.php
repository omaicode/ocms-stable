<?php namespace OCMS\Repository\Transformer;

use League\Fractal\TransformerAbstract;
use OCMS\Repository\Contracts\Transformable;

/**
 * Class ModelTransformer
 * @package OCMS\Repository\Transformer
 * @author Anderson Andrade <contato@andersonandra.de>
 */
class ModelTransformer extends TransformerAbstract
{
    public function transform(Transformable $model)
    {
        return $model->transform();
    }
}
