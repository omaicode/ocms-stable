<?php
namespace OCMS\Repository\Contracts;

/**
 * Interface Transformable
 * @package OCMS\Repository\Contracts
 * @author Anderson Andrade <contato@andersonandra.de>
 */
interface Transformable
{
    /**
     * @return array
     */
    public function transform();
}
