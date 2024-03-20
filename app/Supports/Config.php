<?php
namespace App\Supports;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config as FacadesConfig;
use InvalidArgumentException;
use App\Models\AdminSetting;

class Config
{
    public static function load($refresh = false)
    {
        if(!Helper::isConnectedDatabase()) {
            return false;
        };

        if($refresh) {
            Cache::forget('admin_settings');
        }

        if(!Cache::has('admin_settings')) {
            $settings = Cache::rememberForever('admin_settings', function() {
                return AdminSetting::get();
            });
        } else {
            $settings = Cache::get('admin_settings');
        }
        
        $configs = [];
        foreach($settings as $setting) {
            $formatted_key = str_replace('__', '.', $setting->key);
            $value = $setting->value;

            if(is_numeric($value) && in_array((int)$value, [0, 1], true)) {
                $value = (int)$value === 0 ? false : true;
            }

            $configs[$formatted_key] = $value;
        }

        FacadesConfig::set($configs);
    }

    public static function set(...$args)
    {
        if(isset($args[0]) && is_array($args[0])) {
            foreach($args[0] as $key => $value) {
                AdminSetting::updateOrCreate([
                    'key' => $key
                ], [
                    'value' => is_array($value) ? json_encode($value) : $value
                ]);                
            }
        } else if(isset($args[0]) && isset($args[1])) {
            AdminSetting::updateOrCreate([
                'key' => $args[0]
            ], [
                'value' => is_array($args[1]) ? json_encode($args[1]) : $args[1]
            ]);
        } else {
            throw new InvalidArgumentException("Invalid config arguments.");
        }

        self::load(true);
    }
}