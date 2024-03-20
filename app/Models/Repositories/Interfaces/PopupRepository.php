<?php

namespace App\Models\Repositories\Interfaces;

use OCMS\Repository\Contracts\RepositoryInterface;

/**
 * Interface PopupRepository.
 *
 * @package namespace App\Models\Repositories\Interfaces;
 */
interface PopupRepository extends RepositoryInterface
{
    public function script() : string;
    public function style() : string;
}
