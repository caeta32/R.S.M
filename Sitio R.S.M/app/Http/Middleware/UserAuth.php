<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserAuth
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
        if($request->path()=="login" && $request->session()->has('username')) {
            return redirect('/iniciousuariogratis');

        }
        if($request->path()=="/" && $request->session()->has('username')) {
                return redirect('/iniciousuariogratis');
        }
        if($request->path()=="iniciousuariogratis" && $request->session()->missing('username')) {
            return redirect('/');
        }
        return $next($request);
    }
}
