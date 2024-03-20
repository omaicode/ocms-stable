<?php
namespace App\Tables;

use App\Models\Admin;
use App\Supports\TableBuilder;
use OCMS\TableBuilder\Column;

class AdminTable extends TableBuilder
{
    /**
     * Model namespace
     * @var string
     */    
    protected $model = Admin::class;

    /**
     * Set table header title
     * @var string
     */
    protected string $header_title = 'menu.system.administrators';
    
    /**
     * Show actions
     * @var bool
     */    
    protected bool $show_actions = true;

    public function __construct(array $options = [])
    {
        parent::__construct($options);

        // Set create URL 
        $this->create_url = route('admin.system.administrators.create');

        // Set delete URL (method => POST)
        $this->delete_url = route('admin.system.administrators.deletes');

        // Set edit URL. See documentation for more details
        $this->edit_url = route('admin.system.administrators.edit', ['administrator' => ':id']);

        // Apply custom query
        $this->applyQuery(function($query, $request) {
            if($request->filled('search')) {
                $query
                ->where('name', 'LIKE', '%'.$request->search.'%')
                ->orWhere('username', 'LIKE', '%'.$request->search.'%')
                ->orWhere('email', 'LIKE', '%'.$request->search.'%');
            }

            $query->latest();
        });        
    }

    /**
     * Add columns to table
     *
     * @return Column[] 
     * @throws BindingResolutionException 
     * @throws NotFoundExceptionInterface 
     * @throws ContainerExceptionInterface 
     */
    protected function columns()
    {
        return [
            new Column("name", __('table.info'), fn($admin) => $this->getAdminInfo($admin)),
            new Column("email", __('table.email')),
            new Column("roles", __('table.roles'), fn($admin) => $this->getAdminRoles($admin)),
            new Column("created_at",  __('table.created_at'), fn($admin) => dateFormat($admin->created_at))
        ];
    }  

    private function getAdminInfo($admin)
    {
        $last_login = __('table.last_login_at');
        $last_login_date = $admin->last_login_at ?: 'N/A';
        $avatar = $admin->avatar_url;

        return <<<HTML
        <div class="d-flex align-items-center">
            <div class="position-relative rounded overflow-hidden me-2" style="width: 48px; height: 48px">
                <img src="{$avatar}" class="w-100 h-100">
            </div>
            <div>
                <div class="fw-bold">{$admin->name}</div>
                <div class="text-muted small">Login: {$admin->username}</div>
                <div class="text-muted small">{$last_login}: {$last_login_date}</div>
            </div>
        </div>
        HTML;
    }

    private function getAdminRoles($admin)
    {
        return implode(', ', optional($admin->getRoleNames())->toArray() ?: []);
    }
}