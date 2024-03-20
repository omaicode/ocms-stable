<?php

namespace App\Http\Controllers\Admin;

use ApiResponse;
use App\Models\Repositories\Interfaces\ClientRepository;
use Illuminate\Http\Request;
use App\Contracts\AdminPage;
use App\Models\Client;
use App\Http\Controllers\FormBuilderController;
use App\Tables\ClientTable;
use App\Form\Form;
use Illuminate\Support\Facades\Hash;

class ClientController extends FormBuilderController
{
    protected $request;
    protected $title = 'menu.clients';

    public function __construct(Request $request)
    {
        $this->middleware('can:clients.view', ['index']);
        $this->middleware('can:clients.create', ['store', 'create']);
        $this->middleware('can:clients.edit', ['update', 'edit']);
        $this->middleware('can:clients.delete', ['destroy', 'deletes']);
        $this->middleware('is-not-demo', ['only' => ['store', 'update', 'destroy']]);

        $this->request = $request;
        $this->breadcrumb = [
            [
                'title'  => __('menu.clients'),
                'url'    => route('admin.clients.index'),
            ]
        ];
    }

    public function index()
    {
        $table = new ClientTable();

        return app(AdminPage::class)
        ->title(__($this->title))
        ->breadcrumb($this->breadcrumb)
        ->body($table);
    }

    public function deletes()
    {
        $rows = $this->request->rows;
        Client::whereIn('id', $rows)->delete();

        return ApiResponse::success();
    }

    protected function form()
    {
        $form  = new Form(new Client);

        if($form->isCreating()) {
            $form->title(__('messages.admin.new_client'));
        } else {
            $form->title(__('messages.admin.edit_client'));
        }

        $form->row(function($form) {
            $form->width(4)
                ->text('name', __('messages.admin.name'))
                ->placeholder(__('messages.admin.name_placeholder'))
                ->required()
                ->rules('required|min:3|max:30');

            $form->width(4)
                ->text('phone', __('messages.admin.phone'))
                ->placeholder(__('messages.admin.phone_placeholder'))
                ->required()
                ->rules('required|numeric|digits_between:9,15');

            $form->width(4)
                ->text('email', __('messages.admin.email'))
                ->placeholder(__('messages.admin.email_placeholder'))
                ->required()
                ->creationRules('required|email|unique:admins,email')
                ->updateRules('required|email|unique:admins,email,{{id}},id');

            $form->width(6)
                ->password('password', __('messages.admin.password'))
                ->placeholder(__('messages.admin.password_placeholder'))
                ->creationRules('required|min:8|max:30|confirmed')
                ->updateRules('nullable|min:8|max:30|confirmed');

            $form->width(6)
                ->password('password_confirmation', __('messages.admin.password_confirm'))
                ->placeholder(__('messages.admin.password_confirm_placeholder'));

            $form->checkbox('email_verified_at', __('messages.admin.email_has_been_verified'))->nullable();
        });

        $form->tools(function($tool) {
            $tool->select('active', __('messages.status'))
            ->options([0 => __('messages.inactive'), 1 => __('messages.active')])
            ->default(1);
        });

        $content = __('messages.admin.password_help');

        $form->getLayout()->setCustomContent(<<<HTML
        <ul class="ps-3 text-muted">
            <li class="small">{$content}</li>
        </ul>
        HTML);

        $form->submitted(function($form) {
            $form->ignore(['password', 'password_confirmation', 'email_verified_at']);
        });

        $form->saving(function($form) {
            if($this->request->filled('email_verified_at')) {
                $form->email_verified_at = now();
            } else {
                $form->email_verified_at = null;
            }

            if($this->request->filled('password')) {
                $form->password = Hash::make($this->request->password);
            }
        });

        return $form;
    }

    /**
     * @param Request $req
     * @return mixed
     */
    public function ajax(Request $req)
    {
        return app(ClientRepository::class)
            ->when($req->filled('q'), function($q) use ($req) {
                $q->where('id', $req->get('q'))->orWhere('name', 'LIKE', '%'.$req->get('q').'%');
            })
            ->paginate(null, ['id', 'name as text']);
    }
}
