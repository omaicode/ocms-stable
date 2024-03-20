<?php

namespace App\Http\Controllers\Admin;

use ApiResponse;
use App\Http\Requests\Admin\Settings\UpdateSocialLogin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Contracts\AdminPage;
use App\Facades\Email;
use App\Http\Requests\Admin\Settings\UpdateBackend;
use App\Http\Requests\Admin\Settings\UpdateAnalytics;
use App\Http\Requests\Admin\Settings\UpdateEmail;
use App\Http\Requests\Admin\Settings\UpdateMaintenance;
use App\Http\Requests\Admin\Settings\UpdateSMS;
use App\Supports\Config;
use App\Supports\Helper;
use App\Supports\Media;

class SettingController extends Controller
{
    protected $page;

    public function __construct(AdminPage $page)
    {
        $this->middleware('can:setting.general', ['general', 'updateBackend', 'updateAnalytics', 'updateMaintenance']);
        $this->middleware('can:setting.email', ['email', 'updateEmail']);
        $this->middleware('can:setting.sms', ['sms', 'updateSMS']);

        $this->page = $page;
    }

    public function general()
    {
        $breadcrumb = [['title' => __('menu.settings'), 'url' => '#'], ['title' => __('menu.settings.general'), 'url' => '#']];
        return $this->page
        ->breadcrumb($breadcrumb)
        ->title(__('menu.settings.general'))
        ->body('admin.pages.settings.general');
    }

    public function email()
    {
        $breadcrumb = [['title' => __('menu.settings'), 'url' => '#'], ['title' => __('menu.settings.email'), 'url' => '#']];
        return $this->page
        ->breadcrumb($breadcrumb)
        ->title(__('menu.settings.email'))
        ->push('modal', 'admin.pages.settings.email_modal')
        ->push('scripts', 'admin.pages.settings.email_script')
        ->body('admin.pages.settings.email');
    }

    public function sms()
    {
        $breadcrumb = [['title' => __('menu.settings'), 'url' => '#'], ['title' => __('menu.settings.sms'), 'url' => '#']];
        return $this->page
        ->breadcrumb($breadcrumb)
        ->title(__('menu.settings.sms'))
        ->body('admin.pages.settings.sms');
    }

    public function socialLogin()
    {
        $breadcrumb = [['title' => __('menu.settings'), 'url' => '#'], ['title' => __('menu.settings.social-login'), 'url' => '#']];
        return $this->page
        ->breadcrumb($breadcrumb)
        ->title(__('menu.settings.social-login'))
        ->body('admin.pages.settings.social-login');
    }

    public function updateBackend(UpdateBackend $request)
    {
        $data = $request->only([
            'app__language',
            'app__name',
            'app__debug',
            'app__timezone',
            'app__logo',
            'app__favicon',
            'app__notification_method'
        ]);

        if($request->hasFile('app__logo')) {
            $media = Media::upload($data['app__logo']);
            $data['app__logo'] = $media ? $media['save_path'] : null;
        } else {
            unset($data['app__logo']);
        }

        if($request->hasFile('app__favicon')) {
            $media = Media::upload($data['app__favicon']);
            $data['app__favicon'] = $media ? $media['save_path'] : null;
        } else {
            unset($data['app__favicon']);
        }

        $data['app__debug'] = @$data['app__debug'] == 'on' ? true : false;
        $data['app__cache'] = @$data['app__cache'] == 'on' ? true : false;

        Config::set($data);

        return redirect()->back()->with('toast_success', __('messages.saved'));
    }

    public function updateAnalytics(UpdateAnalytics $request)
    {
        $data = $request->only([
            'analytics__tracking_id',
            'analytics__view_id',
            'analytics__service_account_credentials_json',
        ]);

        Config::set($data);

        return redirect()->back()->with('toast_success', __('messages.saved'));
    }

    public function updateMaintenance(UpdateMaintenance $request)
    {
        $data = $request->only([
            'app__maintenance',
        ]);

        $data['app__maintenance'] = @$data['app__maintenance'] == 'on' ? true : false;

        Config::set($data);

        return redirect()->back()->with('toast_success', __('messages.saved'));
    }

    public function updateEmail(UpdateEmail $request)
    {
        $data = $request->input();
        $data['mail__queue'] = @$data['mail__queue'] == 'on' ? true : false;
        $data['mail__enable'] = @$data['mail__enable'] == 'on' ? true : false;

        Config::set($data);

        return redirect()->back()->with('toast_success', __('messages.saved'));
    }

    public function updateSMS(UpdateSMS $request)
    {
        $data = $request->validated();
        Config::set($data);

        return redirect()->back()->with('toast_success', __('messages.saved'));
    }

    public function getEmailTemplate(Request $request)
    {
        $template = Email::get($request->template);

        if(!$request->filled('template') || !$template) {
            return ApiResponse::error(__('messages.ajax_email_template_error'));
        }

        if($template['type'] == 'view') {
            $template['content'] = Helper::getRawView($template['content'])['content'];
        }

        return ApiResponse::success()->data($template);
    }

    public function updateSocialLogin(UpdateSocialLogin $request)
    {
        $data = $request->validated();

        $data['services__facebook__redirect'] = route('clientarea.auth.login.social-callback', "facebook");
        $data['services__google__redirect'] = route('clientarea.auth.login.social-callback', "google");
        $data['services__kakao__redirect'] = route('clientarea.auth.login.social-callback', "kakao");
        $data['services__naver__redirect'] = route('clientarea.auth.login.social-callback', "naver");

        $data['services__facebook__enabled'] = $request->filled('services__facebook__enabled') ? 1 : 0;
        $data['services__google__enabled'] = $request->filled('services__google__enabled') ? 1 : 0;
        $data['services__kakao__enabled'] = $request->filled('services__kakao__enabled') ? 1 : 0;
        $data['services__naver__enabled'] = $request->filled('services__naver__enabled') ? 1 : 0;

        Config::set($data);

        return redirect()->back()->with('toast_success', __('messages.saved'));
    }
}
