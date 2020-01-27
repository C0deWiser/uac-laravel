<?php

return [
    'server_url' => env('OAUTH_SERVER', 'https://oauth.fc-zenit.ru'),
    'client_id' => env('OAUTH_CLIENT_ID'),
    'client_secret' => env('OAUTH_CLIENT_SECRET'),
    'redirect_uri' => env('OAUTH_REDIRECT_URI', (isset($_SERVER['HTTP_HOST']) ? request()->root() : '') . '/oauth/callback'),
    'default_scopes' => env('OAUTH_SCOPES', '')
];
