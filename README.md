# Laravel UAC Package for any protected FC Zenit Service Application

Пакет предоставляет разработчику `middleware` под названием `auth.oauth`, которым разработчик может закрыть все маршруты, где требуется авторизация пользователя.
Авторизация пользователя происходит на OAuth сервере ФК Зенит.

## Состав

Пакет содержит маршруты, их контроллеры и мидлварю.

## Роуты

`GET /oauth/callback`

Адрес обратного вызова.

`GET /oauth/logout`

Адрес для деавторизации пользователя одновременно на сервере и на сайте. 
Разработчик должен использовать этот роут только в случае необходимости деавторизации именно на сервере.

`GET /oauth`

Сервисный роут, показывает информацию об авторизованном пользователе.

## Настройка

```dotenv
OAUTH_CLIENT_ID=        // ClientId
OAUTH_CLIENT_SECRET=    // ClientSecret
OAUTH_SCOPES=           // Your application default scopes
```

Приложение, которое вы зарегистрируете на OAuth-сервере, будет иметь `redirect_uri`
`http(s)://example.com/oauth/callback`.

Если модель пользователя в вашем приложении отличается от `\App\User::class`, 
то укажите правильную модель

```dotenv
USER_MODEL=\App\Models\User
```

Пропишите `Codewiser\UAC\Laravel\ServiceProvider` в `config/app.php`.

## Переопределение

Пакет пытается авторизовать локального пользователя по совпадению `email`;
если пользователя нет, то пакет добавляет его.

Если у вас в приложении реализована извращенная логика работы с пользователем,
то вы можете переопредлить классы `UacClient` (вас интересует метод `authorizeResourceOwner`) и `AuthenticateWithOauth`,
сделать свой `middleware` и использовать его.


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

## Ещё

Пакет наследует `codewiser/uac`, поэтому разработчикам доступны все способы получения `access_token`, и предоставляется удобный интерфейс доступа к api-ресурсам.
