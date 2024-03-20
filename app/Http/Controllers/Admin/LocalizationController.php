<?php

namespace App\Http\Controllers\Admin;

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

        if($this->request->filled('language') && in_array($this->request->language, $codes)) {
            $this->request->session()->put('current_admin_language', $this->request->language);
        }

        return redirect()->back()->with('toast_success', __('Changed language successfully!'));
    }
}
