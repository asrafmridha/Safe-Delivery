<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Rider
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
        if (!auth()->guard('rider')->check()) {
            session()->flash('message', 'You are Unauthorized to Access. Further action Please Login.');
            session()->flash('type', 'danger');
            return redirect()->route('frontend.login');
        }

        return $next($request);
    }
}
