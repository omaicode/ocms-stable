<?php
namespace App\Tables;

use Illuminate\Support\Carbon;
use App\Supports\TableBuilder;
use OCMS\Permission\Models\Role;
use OCMS\TableBuilder\Column;

class RoleTable extends TableBuilder
{
    /**
     * Model namespace
     * @var string
     */    
    protected $model = Role::class;

    /**
     * Set table header title
     * @var string
     */
    protected string $header_title = 'messages.roles.title';
    
    /**
     * Show actions
     * @var bool
     */    
    protected bool $show_actions = true;

    public function __construct(array $options = [])
    {
        parent::__construct($options);

        // Set create URL 
        $this->create_url = route('admin.system.roles.create');

        // Set delete URL (method => POST)
        $this->delete_url = '/path/to/delete/url';

        // Set edit URL. See documentation for more details
        $this->edit_url = route('admin.system.roles.edit', ['role' => ':id']);

        // Apply custom query
        $this->applyQuery(function($query, $request) {
            $query->where('name', '<>', 'Super Admin');

            if($request->filled('search')) {
                $query->where('name', 'LIKE', '%'.$request->search.'%');
            }
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
            new Column("id", __('messages.roles.id')),
            new Column("name", __('messages.roles.name')),
            new Column("total_permissions", __('messages.roles.total_permissions'), fn($item) => $item->permissions()->count()),
            new Column("created_at", __('messages.roles.created_at'), fn($item) => Carbon::parse($item->created_at)->format('Y-m-d H:i:s'))
        ];
    }  
}