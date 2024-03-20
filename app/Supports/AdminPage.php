<?php
namespace App\Supports;

use AdminAsset;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewParent;
use App\Contracts\AdminPage as AdminPageContract;
use Throwable;

class AdminPage implements Renderable, AdminPageContract
{
    protected $title;
    protected $layout;
    protected $body;
    protected $view;
    protected array $breadcrumb = [];
    protected $global_variables = [];

    public function title(string $title)
    {
        $this->title = $title;

        return $this;
    }

    public function layout(string $layout)
    {
        $this->layout = $layout;

        return $this;
    }

    public function body($view, array $data = [])
    {
        if($view instanceof ViewParent || $view instanceof Htmlable || $view instanceof Renderable) {
            $this->body = $view;
        } else {
            $this->body = View::make($view, $data);
        }

        return $this;
    }

    public function breadcrumb(array $breadcrumb)
    {
        $this->breadcrumb = $breadcrumb;

        return $this;
    }

    public function push($name, $content, $data = [])
    {
        if($content instanceof ViewParent) {
            $content = $content->render();
        } else {
            $content = View::make($content, $data)->render();
        }

        AdminAsset::push($name, $content);

        return $this;
    }

    public function render()
    {
        try {
            $this->initShortcodes();
            $layout     = $this->layout ?: 'admin.'.config('admin.admin_layout', 'default-master');
            $title      = $this->title;
            $body       = (!is_string($this->body) && method_exists($this->body, 'render')) ? $this->body->render() : $this->body;
            $breadcrumb = $this->breadcrumb;
            $global_variables = array_merge([
                'admin_prefix' => config('admin.admin_prefix'),
                'locale' => app()->getLocale()
            ], $this->global_variables);

            return View::make(
                $layout,
                compact('title', 'body', 'breadcrumb', 'global_variables')
            );
        } catch(HttpResponseException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error($e);
            abort(500);
        }
    }

    public function addVariables(array $variables)
    {
        $this->global_variables = array_merge($this->global_variables, $variables);

        return $this;
    }

    private function initShortcodes()
    {
        $class = "\\App\\Facades\\Shortcode";
        if(class_exists($class)) {
            $shortcodes = $class::compiler()->getRegistered();
            $variables  = [];

            foreach($shortcodes as $key => $value) {
                $variables[] = method_exists($value, 'info') ? $value::info() : $key;
            }

            $this->addVariables(['Shortcodes' => $variables]);
        }
    }
}
