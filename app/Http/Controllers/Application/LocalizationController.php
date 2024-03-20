<?php

namespace App\Http\Controllers\Application;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Language;

class LocalizationController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function setLanguage()
    {
        $codes = Language::get()->pluck('code')->toArray();

        if($this->request->filled('language') && in_array($this->request->language, $codes, true)) {
            $this->request->session()->put('current_language', $this->request->language);
        }

        return redirect()->back();
    }
}
