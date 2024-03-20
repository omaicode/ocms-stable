<?php

namespace App\Http\Controllers\Admin;

use ApiResponse;
use Illuminate\Http\Request;
use App\Contracts\AdminPage;
use App\Models\Admin;
use App\Http\Controllers\FormBuilderController;
use App\Tables\AdminTable;
use App\Form\Form;
use App\Media\Support\Uploader;
use OCMS\Permission\Models\Role;

class AdminController extends FormBuilderController
{
    protected $request;
    protected $title = 'menu.system.administrators';

    public function __construct(Request $request)
    {
        $this->middleware('can:system.admins.view', ['index']);
        $this->middleware('can:system.admins.create', ['store', 'create']);
        $this->middleware('can:system.admins.edit', ['update', 'edit']);
        $this->middleware('can:system.admins.delete', ['destroy', 'deletes']);
        $this->middleware('is-not-demo', ['only' => ['store', 'update', 'destroy']]);

        $this->request = $request;
        $this->breadcrumb = [
            [
                'title'  => __('menu.system'),
                'url'    => '#',
            ],
            [
                'title'  => __('menu.system.administrators'),
                'url'    => route('admin.system.administrators.index'),
            ]
        ];
    }

    public function index()
    {
        $table = new AdminTable;

        return app(AdminPage::class)
        ->title(__($this->title))
        ->breadcrumb($this->breadcrumb)
        ->body($table);
    }

    public function deletes()
    {
        $rows = $this->request->rows;

        if(in_array($this->request->user()->id, $rows, true)) {
            return ApiResponse::error(__('messages.admin.deleting_yourself'));
        }

        Admin::whereIn('id', $rows)->where('super_user', false)->delete();

        return ApiResponse::success();
    }

    protected function form()
    {
        $super_user = false;
        $roles = Role::where('guard_name', 'admins')->get()->pluck('name', 'id')->toArray();
        $form  = new Form(new Admin);

        if($form->isCreating()) {
            $form->title(__('messages.admin.new_administrator'));
        } else {
            $super_user = Admin::where('id', $this->request->route('administrator'))->where('super_user', true)->exists();
            $form->title(__('messages.admin.edit_administrator'));
        }

        $form->row(function($form) use ($roles, $super_user) {
            if($form->isEditing()) {
                $form->width(6)->display('id', __('messages.id'));
                $form->width(6)->display('created_at', __('messages.created_at'));
            }

            $form->width(6)
                ->text('name', __('messages.admin.name'))
                ->placeholder(__('messages.admin.name_placeholder'))
                ->required()
                ->rules('required|min:3|max:30');

            $form->width(6)
                ->text('username', __('messages.admin.username'))
                ->placeholder(__('messages.admin.username_placeholder'))
                ->required()
                ->creationRules('required|min:6|max:30|alpha_dash|unique:admins,username')
                ->updateRules('required|min:6|max:30|alpha_dash|unique:admins,username,{{id}},id');

            $form->width(6)
                ->text('email', __('messages.admin.email'))
                ->placeholder(__('messages.admin.email_placeholder'))
                ->required()
                ->creationRules('required|email|unique:admins,email')
                ->updateRules('required|email|unique:admins,email,{{id}},id');

            $form->width(6)
                ->select('role', __('messages.admin.role'))
                ->options($roles)
                ->placeholder(__('messages.admin.role_placeholder'))
                ->default(array_keys($roles)[0])
                ->required()
                ->rules('required|in:'.implode(',', array_keys($roles)))
                ->disable($super_user);

            $form->width(6)
                ->password('password', __('messages.admin.password'))
                ->placeholder(__('messages.admin.password_placeholder'))
                ->creationRules('required|min:8|max:30|confirmed')
                ->updateRules('nullable|min:8|max:30|confirmed');

            $form->width(6)
                ->password('password_confirmation', __('messages.admin.password_confirm'))
                ->placeholder(__('messages.admin.password_confirm_placeholder'));

        });

        $content_1 = __('messages.admin.username_help');
        $content_2 = __('messages.admin.password_help');

        $form->getLayout()->setCustomContent(<<<HTML
        <ul class="ps-3 text-muted">
            <li class="small">{$content_1}</li>
            <li class="small">{$content_2}</li>
        </ul>
        HTML);

        $form->tools(function($tools) {
            $tools
            ->avatar('avatar', __('messages.admin.avatar'))
            ->preview(fn() => $this->model()->avatar_url)
            ->rules('required|file|image|max:3072');
        });

        $form->submitted(function($form) {
            $form->ignore(['role', 'password', 'password_confirmation', 'avatar']);
        });

        $form->saving(function($form) {
            if($this->request->filled('password')) {
                $form->password = $this->request->password;
            }
        });

        $form->saved(function($form) use ($super_user) {
            $model = $form->model();

            if(!$super_user) {
                $model->syncRoles($this->request->role);
            }

            if($this->request->hasFile('avatar')) {
                $media = app(Uploader::class)->upload($this->request->avatar, $model->media_save_path);
                if($media->count() > 0) {
                    $model->update(['avatar' => optional($media->first())->uuid]);
                }
            }
        });

        return $form;
    }
}
