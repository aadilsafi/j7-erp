<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class CrmApiMiddleware
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
        if ($request->route('user_id') != 'ajax') {
            if (decryptParams($request->route('user_id')) > 0) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                Auth::guard('web')->loginUsingId(decryptParams($request->route('user_id')));
            }
        }
        return $next($request);

    }
}
