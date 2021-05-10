<?php

namespace App\Http\Middleware;

use Closure;
use Auth; // get a current user that is authenticated

class VerifyAdmin
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
      if(Auth::user()->checkAdmin() && Auth::check()) { // if admin
        return $next($request); // $next similar to middleware in Node
      }
        return redirect('/login');
    }
}
