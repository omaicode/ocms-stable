<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsNotDemo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(config('admin.is_demo')) return abort(401, __('The feature you are trying to access is not available for demo content.'));
        return $next($request);
    }
}
