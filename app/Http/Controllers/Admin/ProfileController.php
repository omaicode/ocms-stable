<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Contracts\AdminPage;
use App\Models\Admin;
use App\Form\Form;
use App\Media\Support\Uploader;

class ProfileController extends Controller
{
    protected $request;
    protected $page;
    protected $title = 'messages.profile';

    public function __construct(Request $request, AdminPage $page)
    {
        $this->request = $request;
        $this->page    = $page;
    }

    public function index()
    {
        $form = $this->form();
        $breadcrumb = [
            [
                'title'  => __('messages.profile'), 
                'url'    => route('admin.profile'),
            ]
        ];  

        return $this->page
        ->title(__($this->title))
        ->breadcrumb($breadcrumb)
        ->body('admin.pages.profile.index', compact('form'));
    }

    public function update()
    {
        $user = $this->request->user();
        return $this->form()->update($user->id);
    }

    protected function form()
    {
        $user = $this->request->user();
        $form = new Form(new Admin);        

        $form->title(__('messages.profile'));
        $form->setAction(route('admin.profile.update'));
        $form->redirectUrl(route('admin.profile'));

        $form->row(function($form) {
            $form->width(4)->display('id', __('messages.id'));
            $form->width(4)->display('created_at', __('messages.created_at'))->with(fn() => $this->created_at->format('Y-m-d H:i:s'));
            $form->width(4)->display('username', __('messages.admin.username'));
            
            $form->width(6)
                ->text('name', __('messages.admin.name'))
                ->placeholder(__('messages.admin.name_placeholder'))                
                ->required()
                ->rules('required|min:3|max:30');
                
            $form->text('email', __('messages.admin.email'))
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
        });

        $content_2 = __('messages.admin.password_help');

        $form->getLayout()->setCustomContent(<<<HTML
        <ul class="ps-3 text-muted">
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
            $form->ignore(['password', 'password_confirmation', 'avatar']);
        });

        $form->saved(function($form) {
            $model = $form->model();

            if($this->request->hasFile('avatar')) {
                $media = app(Uploader::class)->upload($this->request->avatar, $model->media_save_path);
                if($media->count() > 0) {
                    $model->avatar = optional($media->first())->uuid;
                }
            }

            if($this->request->filled('password')) {
                $model->password = $this->request->password;
            }

            $model->save();
        });
        
        return $form->edit($user->id);        
    }
}