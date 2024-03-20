<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LocalizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->session()->has('current_admin_language') && $request->segment(1) == config('admin.admin_prefix', 'admin')) {
            app()->setLocale($request->session()->get('current_admin_language', config('app.locale', 'en')));
        }

        if($request->session()->has('current_language') && $request->segment(1) != config('admin.admin_prefix', 'admin')) {
            app()->setLocale($request->session()->get('current_language', config('app.locale', 'en')));
        }

        return $next($request);
    }
}
