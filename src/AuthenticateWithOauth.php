<?php

namespace Codewiser\UAC\Laravel;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateWithOauth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $uac = UacClient::Client();

        if (!($uac->hasAccessToken() && Auth::check())) {
            $uac->setReturnPath($request->url());
            return redirect($uac->getAuthorizationUrl());
        }

        return $next($request);
    }
}
