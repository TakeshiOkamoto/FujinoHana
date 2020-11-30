<?php

namespace App\Http\Middleware;

use Closure;
use App\FujiChild;

class LoginMiddleware
{
    public function handle($request, Closure $next)
    {
        // 未ログイン
        if(!session()->has('name')){
            return redirect(url('login'));
        }else{
          // 退会済み子IDの確認
          // ※親IDは管理者なので確認しない
          if(session()->has('childID')){
              $item = FujiChild::where('login_id', session('childID'))->get();
              if(count($item) === 0){
                  session()->forget('name');
                  return redirect(url('login'));
              }          
          }
        }
        return $next($request);
    }
}