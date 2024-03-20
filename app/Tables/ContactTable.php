<?php
namespace App\Tables;

use App\Supports\TableBuilder;
use App\Models\Contact;
use Illuminate\Contracts\Container\BindingResolutionException;
use OCMS\TableBuilder\Column;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ContactTable extends TableBuilder
{
    /**
     * Model namespace
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Set table header title
     * @var string
     */
    protected string $header_title = 'messages.contacts';

    /**
     * Show actions
     * @var bool
     */
    protected bool $show_actions = true;
    protected bool $disable_edit = true;
    protected bool $show_create  = false;

    public function __construct(array $options = [])
    {
        parent::__construct($options);

        // Set delete URL (method => POST)
        $this->delete_url = route('admin.contacts.destroy');
        // Apply custom query
        $this->applyQuery(function($query, $request) {
            if($request->filled('search')) {
                $query->where('name', 'LIKE', '%'.$request->search.'%')
                ->orWhere('phone', 'LIKE',  '%'.$request->search.'%')
                ->orWhere('company_name', 'LIKE',  '%'.$request->search.'%')
                ->orWhere('email', 'LIKE',  '%'.$request->search.'%')
                ->orWhere('subject', 'LIKE',  '%'.$request->search.'%');
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
            new Column("id", __('ID')),
            new Column("sender", __('Sender'), fn($x) => $this->formatSender($x)),
            new Column("content", __('Content'), fn($x) => $this->formatContent($x)),
            new Column("created_at", __('Created at'), fn($item) => $item->created_at->format('Y-m-d H:i:s')),
        ];
    }

    protected function formatSender($contact)
    {
        return <<<HTML
        <div>Name: <b>{$contact->name}</b></div>
        <!-- <div>Company: <b>{$contact->company_name}</b></div> -->
        <div>Email: <b>{$contact->email}</b></div>
        <!-- <div>Phone: <b>{$contact->phone}</b></div> -->
        HTML;
    }

    protected function formatContent($contact)
    {
        $subject = $contact->subject ?: 'None';
        return <<<HTML
        <div>Subject: <b>{$subject}</b></div>
        <div>{$contact->content}</div>
        HTML;
    }
}
