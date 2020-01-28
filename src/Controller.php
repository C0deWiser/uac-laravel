<?php

namespace Codewiser\UAC\Laravel;

use Codewiser\UAC\Exception\OauthResponseException;
use Illuminate\Http\Request;

class Controller extends \Illuminate\Routing\Controller
{

    public function __construct()
    {
        $this->middleware('auth.oauth')->only('info');
    }

    public function callback(Request $request)
    {
        $uac = UacClient::Client();

        try {
            $uac->callbackController($request->all());

            if (!$uac->closePopup()) {
                return redirect($uac->getReturnPath('/'));
            }

        } catch (OauthResponseException $e) {

            if ($e->getMessage() == 'access_denied') {
                if (!$uac->closePopup()) {
                    return redirect($uac->getReturnPath('/'));
                }
            }

            dump($e);
        } catch (\Exception $e) {
            dump($e);
        }

        dump($request->all());
    }

    public function logout(Request $request)
    {
        $uac = UacClient::Client();
        $uac->setReturnPath('/');
        return redirect($uac->getDeauthorizationUrl());
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
