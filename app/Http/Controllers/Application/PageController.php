<?php

namespace App\Http\Controllers\Application;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Repositories\Interfaces\CategoryRepository;
use App\Models\Repositories\Interfaces\PostRepository;
use App\Enums\PageStatusEnum;
use App\Enums\PageTemplateEnum;
use App\Models\Repositories\Interfaces\PageRepository;

class PageController extends Controller
{
    protected $request;
    protected $repository;

    public function __construct(Request $request, PageRepository $repository)
    {
        $this->middleware('theme:'.config('theme.currentTheme'));
        $this->request = $request;
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($slug = null)
    {
        $view = 'home';
        $data = [];

        if(!$slug) {
            $home_page = $this->repository->findWhere([
                'template' => PageTemplateEnum::HOME,
                'status'   => PageStatusEnum::PUBLISH
            ]);

            if($home_page->count() > 0) {
                $view = 'home';
                $data = $home_page->first();
            }
        } else {
            $page = $this->repository->findWhere([
                'slug'    => $slug,
                'status' => PageStatusEnum::PUBLISH
            ]);

            if($page->count() > 0) {
                $view = strtolower(PageTemplateEnum::getKey((int)$page->first()->template));
                $data = $page->first();
            } else {
                $post = app(PostRepository::class)->with('category')->findByField('slug', $slug);
                if($post->count() > 0) {
                    $view = 'post';
                    $data = $post->first();
                } else {
                    return abort(404);
                }
            }
        }
                
        return view($view, $data)->withShortcodes();
    }

    private function prepareDataForSearch(Collection $data, string $type)
    {
        return $data->map(function($item) use ($type) {
            return [
                'name' => $item->title ?: $item->name,
                'slug' => $type == 'category' ? 'c/'.$item->slug : $item->slug,
                'type' => $type
            ];
        })->toArray();
    }

    public function search(Request $req)
    {
        $result = [];
        $keyword = $req->get('search');

        if($req->filled('search')) {
            $categories = app(CategoryRepository::class)->activated()->where('slug', 'like',  '%'.ucfirst($keyword).'%')->limit(5)->get();
            $posts = app(PostRepository::class)->published()->where('slug', 'like',  '%'.ucfirst($keyword).'%')->limit(5)->get();
            $pages = app(PageRepository::class)->published()->where('slug', 'like',  '%'.ucfirst($keyword).'%')->limit(5)->get();

            $pages = $this->prepareDataForSearch($pages, 'page');
            $categories = $this->prepareDataForSearch($categories, 'category');
            $posts = $this->prepareDataForSearch($posts, 'post');

            $result = array_merge($pages, $categories, $posts);
        }

        return view('admin.components.search-result', compact('result'));
    }
}
