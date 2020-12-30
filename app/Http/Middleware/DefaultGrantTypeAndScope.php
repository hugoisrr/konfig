<?php

namespace App\Http\Middleware;

use Closure;

class DefaultGrantTypeAndScope
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
        if (!$request->has('grant_type')) {
            $request->request->add([
                'grant_type' => 'client_credentials'
            ]);
        }

        if (!$request->has('scope')) {
            $request->request->add([
                'scope' => '*'
            ]);
        }

        return $next($request);
    }
}
