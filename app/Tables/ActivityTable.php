<?php
namespace App\Tables;

use Illuminate\Contracts\Container\BindingResolutionException;
use App\Models\AdminActivity;
use App\Supports\TableBuilder;
use OCMS\TableBuilder\Column;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;

class ActivityTable extends TableBuilder
{
    protected $model = AdminActivity::class;

    protected string $header_title = 'menu.system.activities';
    protected bool $show_actions = true;
    protected bool $show_create = false;

    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->delete_url = route('admin.system.activities.delete');

        $this
        ->addRelations('admin')
        ->applyQuery(function($query, $request) {
            $query
            ->when($request->filled('search'), function($q) use ($request) {
                $q
                ->where('agent', 'LIKE', '%'.$request->search.'%')
                ->orWhere('ip_address', 'LIKE', '%'.$request->search.'%');
            })
            ->latest('id');
        });
    }
    
    /**
     * 
     * @return Column[] 
     * @throws BindingResolutionException 
     * @throws NotFoundExceptionInterface 
     * @throws ContainerExceptionInterface 
     */
    protected function columns()
    {
        return [
            (new Column("id", __('ID')))->width('32px')->textAlign('center'),
            (new Column("admin_id", __('Action'), fn($item) => $this->formatAdmin($item)))->width('350px'),
            (new Column("agent", __('Agent')))->maxWidth('400px'),
            new Column("created_at", __('Created at'), fn($item) => $item->created_at->format('Y-m-d H:i:s')),
        ];
    }

    private function formatAdmin($item)
    {
        $ip_address = $item->ip_address ?: '127.0.0.1';
        
        return <<<HTML
        <div>{$item->action_text}</div>
        <div class="text-muted small">{$item->created_at->diffForHumans(now())}</div>
        <div class="text-muted small">IP: {$ip_address}</div>
        HTML;
    }
}