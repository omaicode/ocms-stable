<?php

namespace App\Supports;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class Helper
{
    /**
     * Load helpers from a directory
     * @param string $directory
     * @since 2.0
     */
    public static function autoload(string $directory): void
    {
        $helpers = File::glob($directory . '/*.php');
        foreach ($helpers as $helper) {
            File::requireOnce($helper);
        }
    }

    /**
     * @param string $command
     * @param array $parameters
     * @param null $outputBuffer
     * @return bool|int
     * @throws Exception
     * @deprecated since v5.5, will be removed in v5.7
     */
    public static function executeCommand(string $command, array $parameters = [], $outputBuffer = null): bool
    {
        if (!function_exists('proc_open')) {
            if (config('app.debug') && config('core.base.general.can_execute_command')) {
                throw new Exception(trans('core/base::base.proc_close_disabled_error'));
            }
            return false;
        }

        if (config('core.base.general.can_execute_command')) {
            return Artisan::call($command, $parameters, $outputBuffer);
        }

        return false;
    }

    /**
     * @return bool
     */
    public static function isConnectedDatabase(): bool
    {
        try {
            return Schema::hasTable('admin_settings');
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @return bool
     */
    public static function clearCache(): bool
    {
        Event::dispatch('cache:clearing');

        try {
            Cache::flush();
            if (!File::exists($storagePath = storage_path('framework/cache'))) {
                return true;
            }

            foreach (File::files($storagePath) as $file) {
                if (preg_match('/facade-.*\.php$/', $file)) {
                    File::delete($file);
                }
            }
        } catch (Exception $exception) {
            info($exception->getMessage());
        }

        Event::dispatch('cache:cleared');

        return true;
    }

    /**
     * @return bool
     */
    public static function isActivatedLicense(): bool
    {
        if (!File::exists(storage_path('.license'))) {
            return false;
        }

        // $coreApi = new Core;

        // $result = $coreApi->verifyLicense(true);

        // if (!$result['status']) {
        //     return false;
        // }

        return true;
    }

    /**
     * @return bool|string
     */
    public static function getIpFromThirdParty()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://ipecho.net/plain');
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response ?: Request::ip();
    }

    /**
     * 
     * @param string $name 
     * @return string|false 
     * @throws BindingResolutionException 
     */
    public static function getRawView(string $name, $markdown = true)
    {
        if(View::exists($name)) {
            $name_splited = explode('::', $name);
            $module = '';

            $path      = explode('.', $name_splited[0]);
            $full_path = resource_path("views/");
            $content = file_get_contents($full_path.implode('/',$path).".blade.php");
            $result  = ['module' => $module, 'path' => $full_path, 'full_path' => $full_path.implode('/',$path).".blade.php"];

            if($markdown) {
                $rules   = self::getViewRules();    
                
                $search = $rules->keys()->toArray();
                $replace = $rules->values()->toArray();   
                
                $result['content'] = str_replace($search, $replace, $content);
            } else {
                $result['content'] = $content;
            }

            return $result;
        }

        return null;
    }

    public static function getViewRules()
    {
        return collect([
            '{{' => '==var==',
            '}}' => '==/var==',
            '{!!' => '==raw==',
            '!!}' => '==/raw==',
            '@' => '+',
            '<x-markdown>' => '==markdown==',
            '</x-markdown>'=> '==/markdown=='
        ]);      
    }
}
