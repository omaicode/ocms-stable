<?php

namespace App\Models\Repositories\Interfaces;

use Illuminate\Support\Collection;
use OCMS\Repository\Contracts\RepositoryInterface;

/**
 * Interface MenuRepository.
 *
 * @package namespace App\Models\Repositories;
 */
interface MenuRepository extends RepositoryInterface
{
    public function getAllWithChilds($position): Collection;
    public function getRootMenus($position): Collection;
}
