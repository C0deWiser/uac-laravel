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
            $uac->log("Middleware: no authorized user");
            if (isset($uac->context()->error)) {
                unset($uac->context()->error);
                $uac->log("Middleware: user interrupts authorization process");
                return response('Authorization Required!', 403);
            }
            $uac->log("Middleware: redirect user for authorization");
            $uac->setReturnPath($request->url());
            return redirect($uac->getAuthorizationUrl());
        }

        return $next($request);
    }
}
