<?php

namespace App\Http\Middleware;

use Closure;

class RememberSource
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        //unset($_COOKIE['origin_ref']);

        if(!isset($_COOKIE['origin_ref']))
        {
            if (isset($_SERVER['HTTP_REFERER'])) {
                setcookie('origin_ref', $_SERVER['HTTP_REFERER']);
            }
        }


        return $next($request);
    }
}
