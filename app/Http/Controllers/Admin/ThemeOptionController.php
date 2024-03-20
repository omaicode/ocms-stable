<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Contracts\AdminPage;
use App\Supports\Config;
use App\Supports\Media;

class ThemeOptionController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(AdminPage $page)
    {
        $breadcrumb = [
            [
                'title'  => __('messages.appearance'), 
                'url'    => '#',
            ],            
            [
                'title'  => __('messages.theme_options'), 
                'url'    => route('admin.appearance.theme-options'),
            ]
        ];           

        $theme = config('theme.theme');

        return $page
        ->title(__('messages.theme_options'))
        ->breadcrumb($breadcrumb)
        ->body('admin.pages.theme-options.general', $theme);
    }

    public function seo(AdminPage $page)
    {
        $breadcrumb = [
            [
                'title'  => __('messages.appearance'), 
                'url'    => '#',
            ],            
            [
                'title'  => __('messages.theme_options'), 
                'url'    => route('admin.appearance.theme-options'),
            ],
            [
                'title'  => __('messages.seo'), 
                'url'    => route('admin.appearance.theme-options.seo'),
            ],
        ];            
        $theme = config('theme.theme');

        return $page
        ->title(__('messages.theme_options'))
        ->breadcrumb($breadcrumb)
        ->body('admin.pages.theme-options.seo', $theme);
    }

    public function images(AdminPage $page)
    {
        $breadcrumb = [
            [
                'title'  => __('messages.appearance'), 
                'url'    => '#',
            ],            
            [
                'title'  => __('messages.theme_options'), 
                'url'    => route('admin.appearance.theme-options'),
            ],
            [
                'title'  => __('messages.images'), 
                'url'    => route('admin.appearance.theme-options.images'),
            ],
        ];            
        $theme = config('theme.theme');

        return $page
        ->title(__('messages.theme_options'))
        ->breadcrumb($breadcrumb)
        ->body('admin.pages.theme-options.images', $theme);
    }

    public function socials(AdminPage $page)
    {
        $breadcrumb = [
            [
                'title'  => __('messages.appearance'), 
                'url'    => '#',
            ],            
            [
                'title'  => __('messages.theme_options'), 
                'url'    => route('admin.appearance.theme-options'),
            ],
            [
                'title'  => __('messages.social_links'), 
                'url'    => route('admin.appearance.theme-options.socials'),
            ],
        ];            
        $theme = config('theme.theme');

        return $page
        ->title(__('messages.theme_options'))
        ->breadcrumb($breadcrumb)
        ->body('admin.pages.theme-options.socials', $theme);
    }

    public function update()
    {
        $request_data = $this->request->only([
            'site_name',
            'site_title' ,
            'site_description',
            'site_keywords',
            'seo_title',
            'seo_description',
            'seo_og_image',
            'address',
            'website',
            'email',
            'phone',
            'copyright',
            'logo',
            'logo_light',
            'favicon',
            'page_background',
            'title_background',
            'thumbnail_background',
            'footer_background'   ,
            'facebook',
            'twitter',
            'instagram',
            'youtube',
            'linkedin'  
        ]);
        $data         = [];

        foreach($request_data as $key => $value) {
            $data["theme__theme__{$key}"] = $value;
        }
        
        $file_keys = ['seo_og_image', 'logo', 'logo_light', 'favicon', 'page_background', 'title_background', 'thumbnail_background', 'footer_background'];

        foreach($file_keys as $key) {
            if($this->request->hasFile($key)) {
                $media = Media::upload($data['theme__theme__'.$key]);
                $data['theme__theme__'.$key] = $media ? $media['save_path'] : null;
            } else {
                unset($data['theme__theme__'.$key]);
            }
        }

        Config::set($data);

        return redirect()->back()->with('toast_success', __('messages.saved_changes'));
    }
}
