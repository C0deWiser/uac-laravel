<?php

namespace Codewiser\UAC\Laravel;

use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Codewiser\UAC\Exception\IdentityProviderException as UacIdentityProviderException;

class Controller extends \Illuminate\Routing\Controller
{

    public function __construct()
    {
        $this->middleware('auth.oauth')->only('info');
    }

    public function callback(Request $request)
    {
        try {
            $uac = UacClient::Client();
            $uac->callbackController($request->all());
            echo "<script>window.close();</script>";
            $returnPath = $uac->getReturnPath('/');

            return redirect($returnPath);

        } catch (UacIdentityProviderException $e) {
            dump($e);
        } catch (IdentityProviderException $e) {
            dump($e);
        }

        dump($request->all());
    }

    public function logout(Request $request)
    {
        $uac = UacClient::Client();
        $url = $uac->getDeauthorizationUrl('/');
        return redirect($url);
    }

    public function info(Request $request)
    {
        $uac = UacClient::Client();

        if ($uac->hasAccessToken()) {
            $user = $uac->getResourceOwner();
            dump($user->toArray());
        }
    }
}
