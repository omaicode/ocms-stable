<?php

namespace App\Models\Repositories\Eloquents;

use OCMS\Repository\Eloquent\BaseRepository;
use OCMS\Repository\Criteria\RequestCriteria;
use App\Models\Repositories\Interfaces\TranslateRepository;
use App\Entities\Translate;

/**
 * Class TranslateRepositoryEloquent.
 *
 * @package namespace App\Models\Repositories\Eloquents;
 */
class TranslateRepositoryEloquent extends BaseRepository implements TranslateRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Translate::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
