<?php

namespace App\Http\Controllers\Admin;

use AdminAsset;
use Illuminate\Http\Request;
use Illuminate\Mail\Markdown;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use App\Contracts\AdminPage;
use App\Models\Admin;
use App\Models\AdminActivity;
use Illuminate\Support\Facades\Log;
use Throwable;

use OCMS\LaravelGoogleAnalytics\Facades\LaravelGoogleAnalytics;
use OCMS\LaravelGoogleAnalytics\Period;

class DashboardController extends Controller
{
    public function dashboard(AdminPage $page)
    {
        $module        = collect(json_decode(file_get_contents(base_path('composer.json')), true));
        $total_admins  = Admin::count();
        $activities    = AdminActivity::with('admin')->latest()->limit(10)->get();
        $changelog     = '';
        $changelog_path = base_path('changelog.md');
        $analytics     = collect([]);

        if(File::exists($changelog_path)) {
            $changelog = Markdown::parse(File::get($changelog_path));
        }

        try {
            $analytics = Cache::remember("analytics", 3600, function() {
                return collect(LaravelGoogleAnalytics::dateRange(Period::days(30))
                    ->metrics('totalUsers', 'screenPageViews')
                    ->dimensions('date')
                    ->orderByDimension('date')
                    ->keepEmptyRows(true)
                    ->get()
                    ->table);
            });
        }catch(Throwable $e) {
            Log::error($e);
        }

        AdminAsset::addScript('chartjs', asset('vendor/chartjs/chart.umd.js'));
        AdminAsset::push('scripts', view('admin.components.dashboard_script', compact('analytics')));

        return $page
        ->title(__('menu.dashboard'))
        ->body('admin.pages.dashboard', compact('module', 'total_admins', 'changelog', 'activities', 'analytics'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return redirect()->route('admin.auth.login');
    }
}
