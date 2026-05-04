<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckHost
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
        $allowedHosts = ['helpdesk.audit.gov.my', 'helpdesk.local', 'localhost', '127.0.0.1', 'helpdeskzhafir.test']; // Not a domain yet

        if (!in_array($request->getHost(), $allowedHosts)) {
            abort(403);
        }

        return $next($request);
    }
}