<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsGouverneur
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
        if (auth()->user()->role=='gouverneur'){
            return $next($request);
        }else{
            return redirect('home')->with('erreur','vous n\'avez pas d\'autorisation');
        }
        
    }
}
