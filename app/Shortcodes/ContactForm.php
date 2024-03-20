<?php
namespace App\Shortcodes;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;
use Throwable;

class ContactForm
{
    /**
     * Register shortcode
     * 
     * @param mixed $shortcode 
     * @param mixed $content 
     * @param mixed $compiler 
     * @param mixed $name 
     * @param mixed $viewData 
     * @return void 
     */
    public function register($shortcode, $content, $compiler, $name, $viewData)
    {
        try {
            return view('shortcodes.contact-form', $shortcode)->render();
        } catch (Throwable $e) {
            Log::error($e);
            return '';
        }
    }

    /**
     * Get info of shortcode
     * @return string|array|null 
     * @throws BindingResolutionException 
     */
    public static function info(): array
    {
        return [
            'tag'  => 'contact-form',
            'name' =>  __('messages.contact_form'),
            'attributes' => [
                'style' => 'classic'
            ]
        ];
    }
}