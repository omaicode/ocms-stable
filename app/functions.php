<?php

use App\Models\Repositories\Interfaces\PostRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use App\Enums\MenuPositionEnum;
use App\Models\Repositories\Interfaces\MenuRepository;
use Illuminate\Support\Arr;

if(!function_exists('adminAsset')) {
    function adminAsset($path = '')
    {
        if(substr($path, 0, 1) == '/') {
            $path = substr($path, 1, strlen($path));
        }

        return asset('assets/admin/'.$path);
    }
}

if(!function_exists('uploadPath')) {
    function uploadPath($path = '')
    {
        if(substr($path, 0, 1) == '/') {
            $path = substr($path, 1, strlen($path));
        }

        return asset('uploads/'.$path);
    }
}

if(!function_exists('timezones')) {
    function timezones()
    {
        return json_decode(File::get(storage_path('timezone.json')), true);
    }
}

if(!function_exists('composerPackages')) {
    function composerPackages()
    {
        $composer   = json_decode(file_get_contents(base_path('composer.json')), true);
        $production = collect($composer['require'])->map(fn($version, $name) => [
            'name'    => $name,
            'version' => $version,
            'type'    => 'Production'
        ])->toArray();
        $development = collect($composer['require-dev'])->map(fn($version, $name) => [
            'name'    => $name,
            'version' => $version,
            'type'    => 'Development'
        ])->toArray();

        $packages = array_merge(
            $production,
            $development
        );

        usort($packages, function ($item1, $item2) {
            return $item1['name'] <=> $item2['name'];
        });

        return $packages;
    }
}

if(!function_exists('dateFormat')) {
    function dateFormat($date) {
        return Carbon::parse($date)->format(config('app.date_format', 'Y-m-d H:i:s'));
    }
}

if (!function_exists('prepare_options')) {

    /**
     * @param array $options
     *
     * @return array
     */
    function prepare_options(array $options)
    {
        $original = [];
        $toReplace = [];

        foreach ($options as $key => &$value) {
            if (is_array($value)) {
                $subArray = prepare_options($value);
                $value = $subArray['options'];
                $original = array_merge($original, $subArray['original']);
                $toReplace = array_merge($toReplace, $subArray['toReplace']);
            } elseif (strpos($value, 'function(') === 0) {
                $original[] = $value;
                $value = "%{$key}%";
                $toReplace[] = "\"{$value}\"";
            }
        }

        return compact('original', 'toReplace', 'options');
    }
}

if (!function_exists('json_encode_options')) {
    /**
     * @param array $options
     *
     * @return string
     *
     * @see http://web.archive.org/web/20080828165256/http://solutoire.com/2008/06/12/sending-javascript-functions-over-json/
     */
    function json_encode_options(array $options)
    {
        $data = prepare_options($options);

        $json = json_encode($data['options']);

        return str_replace($data['toReplace'], $data['original'], $json);
    }
}

if (!function_exists('page_title')) {
	/**
	 * Get formatted page title
	 *
	 * @param  bool  $with_app_name
	 * @param  string  $separator
	 * @return string
	 */
	function page_title(string $title, bool $withAppName = true, $separator = '-', $invert = false): string
	{
		if (View::hasSection('title')) {
			$title = View::getSection('title');
		}

		if (!empty($title) && $withAppName) {
			if ($invert) {
				return $title . " " . trim(e($separator)) . " " . config('app.name');
			} else {
				return config('app.name') . " " . trim(e($separator)) . " " . $title;
			}
		} else {
			return config('app.name');
		}
	}
}

if (!function_exists('theme')) {
	/**
	 * Set theme.
	 *
	 * @param  string  $themeName
	 * @return \App\Supports\Theme
	 */
	function theme($themeName = null)
	{
		if ($themeName) {
			\Theme::set($themeName);
		}

		return \Theme::current();
	}
}

if (!function_exists('theme_asset')) {
	/**
	 * Generate an url for the theme asset.
	 *
	 * @param  string  $asset
	 * @param  bool  $absolutePath
	 * @return string
	 */
	function theme_asset(string $asset, $absolutePath = true, bool $version = true)
	{
		return \Theme::url($asset, $absolutePath, $version);
	}
}

if (!function_exists('theme_style')) {
	/**
	 * Generate a secure asset path for the theme asset.
	 *
	 * @param  string  $asset
	 * @param  bool  $absolutePath
	 * @return string
	 */
	function theme_style(string $asset, $absolutePath = true, bool $version = true)
	{
		return \Theme::style($asset, $absolutePath, $version);
	}
}

if (!function_exists('theme_script')) {
	/**
	 * Generate a secure asset path for the theme asset.
	 *
	 * @param  string  $asset
	 * @param  string  $mode
	 * @param  bool  $absolutePath
	 * @param  string  $type
	 * @param  string  $level
	 * @return string
	 */
	function theme_script(string $asset, string $mode = '', $absolutePath = true, string $type = 'text/javascript', string $level = 'functionality', bool $version = true)
	{
		return \Theme::script($asset, $mode, $absolutePath, $type, $level, $version);
	}
}


if (!function_exists('theme_image')) {
	/**
	 * Generate a secure asset path for the theme asset.
	 *
	 * @param  string  $asset
	 * @return string
	 */
	function theme_image(string $asset, string $alt = '', string $class = '', array $attributes = [], $absolutePath = true, bool $version = true)
	{
		return \Theme::image($asset, $alt, $class, $attributes, $absolutePath, $version);
	}
}

if (!function_exists('isValidJson')) {
    function isValidJson(string $str) {
        $str = trim($str);
        if (!empty($str)) {
            @json_decode($str);
            return (json_last_error() === JSON_ERROR_NONE);
        }
        return false;
    }
}

if (!function_exists('primary_menu')) {
    function primary_menu()
    {
        return app(MenuRepository::class)->getAllWithChilds(MenuPositionEnum::PRIMARY_MENU);
    }
}

if (!function_exists('secondary_menu')) {
    function secondary_menu()
    {
        return app(MenuRepository::class)->getAllWithChilds(MenuPositionEnum::SECONDARY_MENU);
    }
}

if (!function_exists('top_menu')) {
    function top_menu()
    {
        return app(MenuRepository::class)->getAllWithChilds(MenuPositionEnum::TOP_MENU);
    }
}

if (!function_exists('get_menu')) {
    function get_menu(string $type = 'primary_menu')
    {
        return app(MenuRepository::class)->getAllWithChilds(MenuPositionEnum::getValue($type));
    }
}

if(!function_exists('get_theme_image')) {
    function get_theme_image($key, $default = null)
    {
        if(blank(config('theme.theme.'.$key))) {
            return $default;
        }

        return uploadPath(config('theme.theme.'.$key));
    }
}

if(!function_exists('get_theme_option')) {
    function get_theme_option($key, $default = null)
    {
        return config('theme.theme.'.$key, $default);
    }
}

if(!function_exists('menu_render')) {
    function menu_render(string $position, $view = 'admin.components.default-menu.index')
    {
        $func = $position.'_menu';
        if(!function_exists($func)) {
            return [];
        }

        if(!View::exists($view)) {
            Log::error("menu_render() -> Menu {$view} not found.");
            return [];
        }

        $menu = $func();
        return View::make($view, compact('menu'))->render();
    }
}

if(!function_exists(('svg'))) {
    function svg(string $path = '')
    {
        $path = rtrim(ltrim($path, "/"), "/");
        $path = theme()->getPath("public/".$path.".svg");

        if(File::exists($path)) {
            return File::get($path);
        }

        return null;
    }
}

if(!function_exists('get_blog_posts')) {
    /**
     * Get blog posts
     * @param int $per_page
     * @param int $page
     * @param array $filter
     * @param string $order_by
     * @param string $order_dir
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    function get_blog_posts(int $per_page = 10, int $page = 1, array $filter = [], string $order_by = 'id', string $order_dir = 'desc')
    {
        return app(PostRepository::class)
        ->published()
        ->with('category')
        ->when(count($filter) > 0, function($q) use ($filter) {
            foreach($filter as $field => $value) {
                if(is_array($value) && count($value) === 3) {
                    $q->where($value[0], $value[1], $value[2]);
                } else {
                    $q->where($field, $value);
                }
            }
        })
        ->orderBy($order_by, $order_dir == 'asc' ? 'asc' : 'desc')
        ->paginate($per_page, ['*'], 'page', $page);
    }
}

if(!function_exists('analytics_script')) {
    function analytics_script() {
        $tracking_id = config('analytics.tracking_id');
        if(!$tracking_id) return '';

        return <<<HTML
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={$tracking_id}"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', '{$tracking_id}');
        </script>
        HTML;
    }
}
