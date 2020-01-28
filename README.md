# Laravel UAC Package for any protected FC Zenit Service Application

Пакет предоставляет разработчику `middleware` под названием `auth.oauth`, которым разработчик может закрыть все маршруты, где требуется авторизация пользователя.
Авторизация пользователя происходит на OAuth сервере ФК Зенит.

## Состав

Пакет содержит маршруты, их контроллеры и мидлварю.

## Настройка

```dotenv
OAUTH_CLIENT_ID=        // ClientId
OAUTH_CLIENT_SECRET=    // ClientSecret
OAUTH_SCOPES=           // Your application default scopes
```

## Использование

С помощью предоставленного мидлваря можно закрыть один роут:

```php
Route::get('/test')->middleware('auth.oauth');
```

Можно закрыть группу роутов:
```php
Route::group(['middleware' => ['auth.oauth']], function() {
    Route::get('/test1');
    Route::get('/test2');
});
```

А можно вообще добавить этот мидлварь в группу `web`, тогда весь сайт будет закрыт от неавторизованного доступа. 

```php
protected $middlewareGroups = [
    'web' => [
        // ...
        \Codewiser\UAC\Laravel\AuthenticateWithOauth:class,
    ],
];
```

## Роуты

`GET /oauth/callback`

Адрес обратного вызова.

`GET /oauth/logout`

Адрес для деавторизации пользователя одновременно на сервере и на сайте. 
Разработчик должен использовать этот роут только в случае необходимости деавторизации именно на сервере.

`GET /oauth`

Сервисный роут, показывает информацию об авторизованном пользователе.

## Ещё

Пакет наследует `codewiser/uac`, поэтому разработчикам доступны все способы получения `access_token`, и предоставляется удобный интерфейс доступа к api-ресурсам.
