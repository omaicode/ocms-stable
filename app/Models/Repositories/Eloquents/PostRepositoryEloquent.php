<?php

namespace App\Models\Repositories\Eloquents;

use OCMS\Repository\Eloquent\BaseRepository;
use OCMS\Repository\Criteria\RequestCriteria;
use App\Models\Repositories\Interfaces\PostRepository;
use App\Models\Post;

/**
 * Class PostRepositoryEloquent.
 *
 * @package namespace App\Models\Repositories\Eloquents;
 */
class PostRepositoryEloquent extends BaseRepository implements PostRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Post::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
    /**
     * Get latest posts
     * @param mixed $category_slug
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLatestPosts($category_slug = null, $limit = 4, array $except = [])
    {
        return $this->published()
        ->when($category_slug !== null, function($q) use ($category_slug) {
            $q->whereHas('category', function($sub_query) use ($category_slug) {
                $sub_query->where('slug', $category_slug)->orWhere('id', $category_slug);
            });
        })
        ->when(count($except) > 0, function($q) use ($except) {
            $q->whereNotIn('id', $except);
        })
        ->latest()
        ->limit($limit)
        ->get();
    }

    public function getPaginate()
    {
        $req = request();
        return $this->published()
        ->when($req->filled('search'), function($q) use ($req) {
            $q->where('title', 'LIKE', '%'.$req->search.'%');
        })
        ->paginate($req->get('per_page', 5));
    }
}
