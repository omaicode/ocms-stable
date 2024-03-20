<?php
namespace App\Shortcodes;

use DOMDocument;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;
use Throwable;

class SubPartial
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
            $content = $this->removeFirstDiv($content);
            return view('partials.'.$shortcode->name, ['data' => $shortcode, 'content' => $content])->render();
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
            'tag'  => 'sub-partial',
            'name' =>  __('messages.shortcodes.sub-partial'),
            'attributes' => [
                'name' => 'partial_name'
            ]
        ];
    }

    public function removeFirstDiv($content)
    {
        $dom = new DOMDocument();
        $dom->loadHTML($content ?: "<div></div>", LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $dom->encoding= 'UTF-8';
        $divList = $dom->getElementsByTagName('div');

        if ($divList->length > 0) {
            $firstDiv = $divList->item(0);    
            $innerHtml = '';

            foreach ($firstDiv->childNodes as $childNode) {
                $html = $dom->saveHTML($childNode);
                $html = mb_convert_encoding($innerHtml, 'HTML-ENTITIES', 'UTF-8');
                $innerHtml .= $html;
            }                  

            return $innerHtml;
        }

        return $content;      
    }
}