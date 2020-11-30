<?php

namespace App\Http\Middleware;

use Closure;

class AdminLoginMiddleware
{
    public function handle($request, Closure $next)
    {
        // 未ログイン(管理者)
        if(!session()->has('parentID')){
            return redirect(url('login'));
        }

        return $next($request);
    }
}