<?php

namespace App\Models\Repositories\Eloquents;

use OCMS\Repository\Eloquent\BaseRepository;
use OCMS\Repository\Criteria\RequestCriteria;
use App\Models\Repositories\Interfaces\AdminActivityRepository;
use App\Models\AdminActivity;

/**
 * Class AdminActivityRepositoryEloquent.
 *
 * @package namespace App\Models\Repositories\Eloquents;
 */
class AdminActivityRepositoryEloquent extends BaseRepository implements AdminActivityRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AdminActivity::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
