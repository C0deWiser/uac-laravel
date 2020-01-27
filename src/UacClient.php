<?php
namespace Codewiser\UAC\Laravel;

use Codewiser\UAC\Connector;
use Codewiser\UAC\Model\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class UacClient extends \Codewiser\UAC\AbstractClient
{
    /**
     * @return static
     */
    public static function Client()
    {
        if (!config('uac.server_url')) {
            throw new \RuntimeException('OAUTH_SERVER env required');
        }
        if (!config('uac.client_id')) {
            throw new \RuntimeException('OAUTH_CLIENT_ID env required');
        }
        if (!config('uac.client_secret')) {
            throw new \RuntimeException('OAUTH_CLIENT_SECRET env required');
        }

        $connector = new Connector(
            config('uac.server_url'),
            config('uac.client_id'),
            config('uac.client_secret'),
            config('uac.redirect_uri'),
            new ContextManager()
        );

        return new static($connector);
    }

    /**
     * Authorize local user
     *
     * @param User|ResourceOwnerInterface $user
     * @throws IdentityProviderException
     */
    protected function authorizeResourceOwner($user)
    {
        $email = null;

        if (@$user->login && filter_var($user->login, FILTER_VALIDATE_EMAIL)) {
            $email = $user->login;
        } elseif (is_string($user->email)) {
            $email = $user->email;
        } elseif (is_array($user->email)) {
            $email = $user->email[0];
        }

        if (!$email) {
            throw new IdentityProviderException("Can not authorize user", 0, null);
        }

        /** @var \App\User $local */
        $local = \App\User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $user->name ?: $email,
                'password' => Hash::make(Str::random())
            ]
        );

        Auth::login($local, true);

        $this->log("Authorize user", $local->toArray());
    }

    /**
     * Deauthorize local user
     */
    protected function deauthorizeResourceOwner()
    {
        Auth::logout();
    }

    /**
     * Log record
     *
     * @param $message
     * @param array $context
     * @return mixed
     */
    public function log($message, array $context = [])
    {
        // TODO: Implement log() method.
        // Log::info($message, $context);
    }

    /**
     * Returns default scopes
     * @return string|array|null
     */
    public function defaultScopes()
    {
        return config('uac.default_scopes');
    }

}