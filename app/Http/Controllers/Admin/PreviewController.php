<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PageTemplateEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class PreviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('theme:'.config('theme.currentTheme'));
    }

    public function index(Request $request)
    {
        if($request->session()->has(['preview_data', 'preview_model'])) {
            $data = $request->session()->get('preview_data', []);
            $data['id'] = 0;
            $data['created_at'] = now()->format('Y-m-d H:i:s');

            if(!isset($data['content'])) {
                $data['content'] = '';
            }

            $model = $request->session()->get('preview_model');
            $request->session()->forget(['preview_data', 'preview_model']);

            if($model === 'Page') {
                $view = strtolower(PageTemplateEnum::getKey((int)Arr::get($data, 'template', 0)));
            } else if($model === 'Post') {
                $no_html = strip_tags(Arr::get($data, 'content', ''));
                $limit = 0;

                if(strlen($no_html) >= 199) {
                    $limit = 199;
                }

                if(strlen($no_html) >= 99) {
                    $limit = 99;

                }

                $data['short_description'] = substr($no_html, 0, $limit).'...';
                $data['image_url'] = Arr::get($data, 'image_url', null);
                $view = 'post';
            } else {
                $view = 'blank';
            }

            return view($view, $data)->withShortcodes();
        }

        if($request->filled('data') && $request->filled('model')) {
            $request->session()->put('preview_data', $request->get('data'));
            $request->session()->put('preview_model', $request->get('model'));
            return 'OK';
        }

        return abort(404);
    }

    public function partial(Request $req)
    {
        if(!$req->filled('partial')) return abort(404);
        if(!$this->isBase64Encoded($req->get('partial', ''))) return abort(400);

        $partial_path = base64_decode($req->get('partial'));
        $partial = '';

        if(File::exists($partial_path) && File::isFile($partial_path)) {
            $paths = explode("/", $partial_path);
            $folder_index = array_search("partials", $paths);
            $new_paths = array_slice($paths, $folder_index + 1, count($paths) -1);
            $new_paths = implode("/", $new_paths);
            $new_paths = str_replace(".blade.php", "", $new_paths);
            $partial = $new_paths;
        }

        return view('partial', ['partial' => $partial])->withShortcodes();
    }

    private function isBase64Encoded(string $s) : bool
    {
        if ((bool) preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s) === false) {
            return false;
        }

        $decoded = base64_decode($s, true);
        if ($decoded === false) {
            return false;
        }

        $encoding = mb_detect_encoding($decoded);
        if (! in_array($encoding, ['UTF-8', 'ASCII'], true)) {
            return false;
        }

        return $decoded !== false && base64_encode($decoded) === $s;
    }
}
