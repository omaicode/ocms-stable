<?php

namespace App\Http\Controllers\Admin;

use ApiResponse;
use App\Facades\AdminAsset;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Enums\MenuPositionEnum;
use App\Http\Requests\Admin\StoreMenuRequest;
use App\Http\Requests\Admin\UpdateMenuOrderRequest;
use App\Models\Repositories\Interfaces\MenuRepository;
use App\Contracts\AdminPage;
use Throwable;

class MenuController extends Controller
{
    /**
     *
     * @var Request
     */
    protected Request $request;

    /**
     *
     * @var MenuRepository
     */
    protected MenuRepository $repository;

    /**
     *
     * @var array
     */
    protected array $breadcrumb;

    /**
     *
     * @var AdminPage
     */
    protected AdminPage $page;

    public function __construct(Request $request, MenuRepository $repository, AdminPage $page)
    {
        $this->request    = $request;
        $this->repository = $repository;
        $this->page       = $page;
        $this->breadcrumb = [
            [
                'title'  => __('messages.appearance'),
                'url'    => '#',
            ],
            [
                'title'  => __('messages.menus'),
                'url'    => route('admin.appearance.menus.index'),
            ]
        ];

        $this->middleware('is-not-demo', ['only' => ['store']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $position = $this->request->get('position', MenuPositionEnum::PRIMARY_MENU);
        $menus = $this->repository->getAllWithChilds($position);
        $root_menus = $this->repository->getRootMenus($position);
        $sub_menus = $this->repository->findWhere([
            ['parent_id', 'IN', $root_menus->pluck('id')->toArray()]
        ]);

        $root_menus = $root_menus->merge($sub_menus);
        $data = compact('menus', 'root_menus', 'position');

        return $this->page
        ->push("scripts", "admin.pages.menu.scripts", $data)
        ->push('modal', "admin.pages.menu.modal", $data)
        ->title(__('messages.menus'))
        ->breadcrumb($this->breadcrumb)
        ->body('admin.pages.menu.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(StoreMenuRequest $request)
    {
        $menu = null;
        $data = $request->only([
            'position',
            'name',
            'url',
            'icon',
            'active',
            'parent_id',
            'order',
            'template'
        ]);

        try {
            DB::beginTransaction();
            if($request->filled('menu_id')) {
                $menu = $this->repository->findByField('id', $request->menu_id);
            }

            if(isset($data['active']) && $data['active'] == 'on') {
                $data['active'] = true;
            } else {
                $data['active'] = false;
            }

            $data['url']  = strlen($data['url']) > 1 ? ltrim($data['url'], '/') : $data['url'];
            if($data['parent_id'] == 0) $data['parent_id'] = null;

            if(!blank($menu)) {
                $menu->first()->update($data);
            } else {
                $this->repository->create($data);
            }

            Cache::forget('menu-'.$data['position']);
            DB::commit();
            return redirect()
            ->route('admin.appearance.menus.index', ['position' => $request->position])
            ->with('toast_success', __('messages.saved_changes'));
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->with('toast_error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id = null)
    {
        if($id) {
            $menu = $this->repository->find($id);
            if($menu) {
                Cache::forget('menu-'.$menu->position);
                $menu->delete();
            }

            return ApiResponse::success("Deleted menu item");
        }

        return ApiResponse::error("Menu item not found");
    }

    public function updateOrder(UpdateMenuOrderRequest $request)
    {
        $parent = $request->parent_id ?: null;
        $item = $request->item_id;

        // Update current menu parent
        $menu = $this->repository->find($item);
        $menu->parent_id = $parent;
        $menu->save();

        // Re-sort
        foreach($request->ordered_list as $order => $id) {
            $this->repository->find($id)->update([
                'order' => $order
            ]);
        }

        Cache::forget('menu-'.$request->get('position', 0));
        return ApiResponse::success();
    }
}
