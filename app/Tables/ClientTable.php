<?php
namespace App\Tables;

use App\Models\Client;
use App\Supports\TableBuilder;
use Illuminate\Contracts\Container\BindingResolutionException;
use OCMS\TableBuilder\Column;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ClientTable extends TableBuilder
{
    /**
     * Model namespace
     * @var string
     */
    protected $model = Client::class;

    /**
     * Set table header title
     * @var string
     */
    protected string $header_title = 'menu.clients';

    /**
     * Show actions
     * @var bool
     */
    protected bool $show_actions = true;

    public function __construct(array $options = [])
    {
        parent::__construct($options);

        // Set create URL
        $this->create_url = route('admin.clients.create');

        // Set delete URL (method => POST)
        $this->delete_url = route('admin.clients.destroy');

        // Set edit URL. See documentation for more details
        $this->edit_url = route('admin.clients.edit', ['client' => ':id']);

        // Apply custom query
        $this->applyQuery(function($query, $request) {
            if($request->filled('search')) {
                $query
                ->where('name', 'LIKE', '%'.$request->search.'%')
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
            new Column("name", __('table.profile'), fn($client) => $this->getClientInfo($client)),
            new Column("provider", __('table.account_type'), fn($client) => $this->getAccountTypeHtml($client)),
            new Column("email", __('table.email_status'), fn($client) => $this->getEmailStatusHtml($client)),
            new Column("created_at",  __('table.created_at'), fn($client) => dateFormat($client->created_at))
        ];
    }

    private function getClientInfo($client) : string
    {
        $last_login = __('table.created_at');
        $last_login_date = $client->created_at->format('Y-m-d H:i:s') ?: 'N/A';

        return <<<HTML
            <div class="fw-bold">{$client->name}</div>
            <div class="text-muted small">Email: {$client->email}</div>
            <div class="text-muted small">{$last_login}: {$last_login_date}</div>
        HTML;
    }

    private function getEmailStatusHtml($client) : string
    {
        if($client->email_verified_at) {
            return '<i class="fas fa-check-circle fa-2x text-success"></i>';
        } else {
            return '<i class="fas fa-times-circle fa-2x text-danger"></i>';
        }
    }

    private function getAccountTypeHtml($client) : string
    {
        return optional($client->provider)->iconHTML() ?: '-';
    }
}
