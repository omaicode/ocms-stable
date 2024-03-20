<?php

namespace App\Models\Repositories\Eloquents;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;
use OCMS\Repository\Eloquent\BaseRepository;
use OCMS\Repository\Criteria\RequestCriteria;
use App\Models\Repositories\Interfaces\MenuRepository;
use App\Models\Menu;

/**
 * Class MenuRepositoryEloquent.
 *
 * @package namespace App\Models\Repositories\Eloquents;
 */
class MenuRepositoryEloquent extends BaseRepository implements MenuRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Menu::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
    /**
     * 
     * @param mixed $position 
     * @return Collection 
     * @throws InvalidArgumentException 
     */
    public function getAllWithChilds($position): Collection
    {
        if(Cache::has('menu-'.$position)) {
            return Cache::get('menu-'.$position);
        }

        return Cache::rememberForever('menu-'.$position, function() use ($position) {
            return $this->getModel()
            ->with(['childs' => fn($q) => $q->where('active', true)])
            ->where('active', true)
            ->whereNull('parent_id')
            ->where('position', $position)
            ->orderBy('order', 'ASC')
            ->get();
        });
    }

    public function getRootMenus($position): Collection
    {
        return $this->findWhere(['position' => $position, 'parent_id' => null, 'active' => true]);
    }
}
