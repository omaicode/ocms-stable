<?php

namespace App\Models\Repositories\Eloquents;

use OCMS\Repository\Eloquent\BaseRepository;
use OCMS\Repository\Criteria\RequestCriteria;
use App\Models\Repositories\Interfaces\PageRepository;
use App\Models\Page;

/**
 * Class PageRepositoryEloquent.
 *
 * @package namespace App\Models\Repositories\Eloquents;
 */
class PageRepositoryEloquent extends BaseRepository implements PageRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Page::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
