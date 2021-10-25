<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // You can optimize / cache this probably so you aren't actually checking for it on every request.
        // Not sure if that is expensive really / probably not.  Depends on what scale you're working with.
        $lang = Cookie::get('lang');
        if($lang && $lang !== App::getLocale())
            App::setLocale( Cookie::get('lang') ?: config('app.locale') );
        return $next($request);
    }
}
