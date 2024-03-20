<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => config('admin.admin_prefix', 'admin'),
    'middleware' => 'auth:admins',
    'as'         => 'admin.',
], function($router) {
    $router->get('/', 'DashboardController@dashboard')->name('dashboard');
    $router->get('/account/logout', 'DashboardController@logout')->name('logout');
    $router->get('/account/profile', 'ProfileController@index')->name('profile');
    $router->put('/account/profile', 'ProfileController@update')->name('profile.update')->middleware('is-not-demo');

    /*
    |--------------------------------------------------------------------------
    | Settings Routes
    |--------------------------------------------------------------------------
    */
    $router->prefix('settings')->as('settings.')->group(function($router) {
        $router->get('general', 'SettingController@general')->name('general')->can('setting.general');
        $router->get('email', 'SettingController@email')->name('email')->can('setting.email');
        $router->get('sms', 'SettingController@sms')->name('sms')->can('setting.sms');
        $router->get('social-login', 'SettingController@socialLogin')->name('social-login')->can('setting.social-login');

        $router->post('backend', 'SettingController@updateBackend')->name('backend.post')->can('setting.general')->middleware('is-not-demo');
        $router->post('analytics', 'SettingController@updateAnalytics')->name('analytics.post')->can('setting.general')->middleware('is-not-demo');
        $router->post('maintenance', 'SettingController@updateMaintenance')->name('maintenance.post')->can('setting.general')->middleware('is-not-demo');
        $router->post('email', 'SettingController@updateEmail')->name('email.post')->can('setting.email')->middleware('is-not-demo');
        $router->post('sms', 'SettingController@updateSMS')->name('sms.post')->can('setting.sms')->middleware('is-not-demo');
        $router->post('social-login', 'SettingController@updateSocialLogin')->name('social-login.post')->can('setting.social-login')->middleware('is-not-demo');
        $router->post('ajax/email-template', 'SettingController@getEmailTemplate')->name('email.template')->can('setting.email')->middleware('is-not-demo');
    });

    /*
    |--------------------------------------------------------------------------
    | System Routes
    |--------------------------------------------------------------------------
    */
    $router->prefix('system')->as('system.')->group(function($router) {
        $router->resource('administrators', 'AdminController');
        $router->resource('roles', 'RoleController', ['only' => ['index', 'create', 'edit', 'store', 'update']]);

        $router->get('information', 'SystemController@information')->name('information')->can('system.information.view');
        $router->get('activities', 'SystemController@activities')->name('activities')->can('system.activity.view');
        $router->get('logs', 'SystemController@logs')->name('logs')->can('system.error_log.view');

        $router->post('administrators/deletes', 'AdminController@deletes')->name('administrators.deletes')->middleware('is-not-demo');
        $router->post('activities/delete', 'SystemController@deleteActivity')->name('activities.delete')->middleware('is-not-demo');
    });

    /*
    |--------------------------------------------------------------------------
    | Media Routes
    |--------------------------------------------------------------------------
    */
    $router->prefix('media')->as('media.')->group(function($router) {
        $router->get('/', 'MediaController@index')->name('index');
        $router->prefix('api')->middleware('is-not-demo')->group(function($router) {
            $router->post('list', 'MediaController@list')->name('list');
            $router->post('upload', 'MediaController@upload')->name('upload');
            $router->post('create-folder', 'MediaController@createFolder')->name('create-folder');
            $router->post('move', 'MediaController@move')->name('move');
            $router->post('delete', 'MediaController@destroy')->name('delete');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Theme Routes
    |--------------------------------------------------------------------------
    */
    $router->group([
        'as'        => 'appearance.'
    ], function($router) {
        //Theme options
        $router->get('/themes', 'ThemeController@index')->name('themes');
        $router->get('/theme-options', 'ThemeOptionController@index')->name('theme-options');
        $router->get('/theme-options/seo', 'ThemeOptionController@seo')->name('theme-options.seo');
        $router->get('/theme-options/images', 'ThemeOptionController@images')->name('theme-options.images');
        $router->get('/theme-options/social-links', 'ThemeOptionController@socials')->name('theme-options.socials');

        //Themes
        $router->post('/themes/set', 'ThemeController@setTheme')->name('themes.set')->middleware('is-not-demo');
        $router->post('/theme-options', 'ThemeOptionController@update')->name('theme-options.save')->middleware('is-not-demo');

        //Menu
        $router->post('/menus/update-order', 'MenuController@updateOrder')->name('menus.update-order')->middleware('is-not-demo');
        $router->delete('/menus/{id?}', 'MenuController@destroy')->name('menus.destroy')->middleware('is-not-demo');
        $router->resource('menus', 'MenuController', ['except' => ['update', 'edit', 'create', 'destroy']]);

        //Partials
        $router->prefix('partials')->as('partials.')->group(function($router) {
            $router->get('/', 'PartialController@index')->name('index');
            $router->get('/tree', 'PartialController@getTree')->name('tree');
            $router->post('/content', 'PartialController@getContent')->name('content')->middleware('is-not-demo');
            $router->put('/content', 'PartialController@saveContent')->name('content.save')->middleware('is-not-demo');
            $router->post('/delete', 'PartialController@destroy')->name('content.delete')->middleware('is-not-demo');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Contact Routes
    |--------------------------------------------------------------------------
    */
    $router->prefix('contacts')->as('contacts.')->group(function($router) {
        $router->get('/', 'ContactController@index')->name('index');
        $router->post('delete', 'ContactController@destroy')->name('destroy')->middleware('is-not-demo');
    });

    /*
    |--------------------------------------------------------------------------
    | Localization Routes
    |--------------------------------------------------------------------------
    */
    $router->post('localization/switch', 'LocalizationController@setLanguage')->name('localization.switch');

    /*
    |--------------------------------------------------------------------------
    | Page Routes
    |--------------------------------------------------------------------------
    */
    $router->post('pages/delete', 'PageController@deletes')->name('pages.destroy');
    $router->resource('pages', 'PageController', ['except' => ['destroy', 'show']]);

    /*
    |--------------------------------------------------------------------------
    | Blog Routes
    |--------------------------------------------------------------------------
    */
    $router->prefix('blog')->as('blog.')->group(function($router) {
        $router->post('categories/destroy', 'CategoryController@deletes')->name('categories.destroy')->middleware('is-not-demo');
        $router->post('posts/destroy', 'PostController@deletes')->name('posts.destroy')->middleware('is-not-demo');
        $router->resource('categories', 'CategoryController', ['except' => 'destroy']);
        $router->resource('posts', 'PostController', ['except' => 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Popup Routes
    |--------------------------------------------------------------------------
    */
    $router->post('popup/destroy', 'PopupController@deletes')->name('popup.destroy')->middleware('is-not-demo');
    $router->resource('popup', 'PopupController', ['except' => 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Client Routes
    |--------------------------------------------------------------------------
    */
    $router->get('clients/ajax', 'ClientController@ajax')->name('clients.ajax');
    $router->post('clients/destroy', 'ClientController@deletes')->name('clients.destroy')->middleware('is-not-demo');
    $router->resource('clients', 'ClientController', ['except' => 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Other Routes
    |--------------------------------------------------------------------------
    */
    $router->any('preview', 'PreviewController@index')->name('preview_url');
    $router->any('preview-partial', 'PreviewController@partial')->name('preview_partial');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix'     => config('admin.admin_prefix', 'admin'),
    'middleware' => 'guest:admins',
    'as'         => 'admin.',
], function($router) {
    $router->prefix('auth')->as('auth.')->group(function($router) {
        $router->get('login', 'AuthController@login')->name('login');
        $router->get('forgot-password', 'AuthController@forgot')->name('forgot');
        $router->get('reset-password/{token}', 'AuthController@reset')->name('reset');

        $router->post('login', 'AuthController@postLogin')->name('login.post');
        $router->post('forgot', 'AuthController@postForgot')->name('forgot.post')->middleware('is-not-demo');
        $router->post('reset-password/{token}', 'AuthController@postReset')->name('reset.post')->middleware('is-not-demo');
    });
});

/*
|--------------------------------------------------------------------------
| Ajax Routes
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'ajax',
    'middleware' => 'auth:admins',
    'as' => 'admin.ajax.',
], function($router) {
    $router->post('clear-cache', 'AjaxController@clearCache')->name('clear-cache');

    $router->prefix('email-template')->as('email-template.')->middleware('is-not-demo')->group(function($router) {
        $router->post('preview', 'AjaxController@previewEmailTemplate')->name('preview');
        $router->post('update', 'AjaxController@updateEmailTemplate')->name('update');
    });
});
// Route::get('email-template', function() {
//     return view('core::email_templates.reset_password', [
//         'subject' => 'Demo'
//     ]);
// });
