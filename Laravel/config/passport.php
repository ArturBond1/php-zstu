<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OAuth 2.0 Clients
    |--------------------------------------------------------------------------
    |
    | Here you may define the configuration of your OAuth 2.0 clients.
    | You may define several clients for different types of applications
    | such as web, mobile, and console. You may also define the grant
    | types that should be enabled for each client.
    |
    */

    'clients' => [
        // [
        //     'id' => 'your-client-id',
        //     'name' => 'Your Client Name',
        //     'secret' => 'your-client-secret',
        //     'redirect' => 'http://localhost:8000/callback',
        //     'grant_types' => ['authorization_code', 'password', 'refresh_token', 'implicit'],
        //     'scopes' => [],
        //     'provider' => null, // Зазвичай null для власних клієнтів
        //     'confidential' => true, // Вказує, чи клієнт є конфіденційним (має секрет)
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Authorization Codes
    |--------------------------------------------------------------------------
    |
    | Here you may define the configuration for authorization codes.
    | This includes the length of the code and the lifetime of the
    | code in seconds.
    |
    */

    'code' => [
        'length' => 32,
        'lifetime' => 300, // 5 хвилин
    ],

    /*
    |--------------------------------------------------------------------------
    | Access Tokens
    |--------------------------------------------------------------------------
    |
    | Here you may define the configuration for access tokens.
    | This includes the length of the token and the lifetime of the
    | token in seconds.
    |
    */

    'tokens' => [
        'length' => 60,
        'lifetime' => 3600, // 1 година
        'refresh_token_lifetime' => 86400 * 30, // 30 днів
        'revoke_tokens_on_password_change' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Refresh Tokens
    |--------------------------------------------------------------------------
    |
    | Here you may define the configuration for refresh tokens.
    | This includes the length of the token. The lifetime is configured
    | in the 'tokens' section.
    |
    */

    'refresh_tokens' => [
        'length' => 100,
    ],

    /*
    |--------------------------------------------------------------------------
    | Personal Access Tokens
    |--------------------------------------------------------------------------
    |
    | Personal access tokens provide a convenient way to authenticate users
    | for your own first-party applications without going through the
    | entire OAuth 2.0 authorization code grant flow.
    |
    */

    'personal_access_tokens' => [
        'table' => 'personal_access_tokens',
        'expiration' => 3600, // 1 година (за замовчуванням null - безстроково)
    ],

    /*
    |--------------------------------------------------------------------------
    | Implicit Grant
    |--------------------------------------------------------------------------
    |
    | The implicit grant type is a simplified authorization flow that does
    | not require the client to exchange an authorization code for an
    | access token. This grant type is typically used for mobile and
    | JavaScript based applications.
    |
    */

    'implicit' => [
        'lifetime' => 3600, // 1 година
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Grant
    |--------------------------------------------------------------------------
    |
    | The password grant type is a convenient way to authenticate users
    | for your own first-party applications. However, it is generally
    | not recommended for third-party applications due to security
    | concerns.
    |
    */

    'password_grant' => [
        'enabled' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Authorization Server Endpoints
    |--------------------------------------------------------------------------
    |
    | Here you may define the URIs for your authorization server's endpoints.
    | These endpoints are used by clients to initiate authorization
    | requests and exchange authorization codes for access tokens.
    |
    */

    'authorization_endpoint' => '/oauth/authorize',

    'token_endpoint' => '/oauth/token',

    'token_info_endpoint' => '/oauth/tokeninfo',

    'keys' => [
        'public' => storage_path('oauth-public.key'),
        'private' => storage_path('oauth-private.key'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    |
    | Here you may define the scopes that are supported by your authorization
    | server. Scopes provide a mechanism for limiting the access that is
    | granted to clients when they are requesting authorization.
    |
    */

    'scopes' => [
        // 'place-orders' => 'Place new orders',
        // 'check-status' => 'Check order status',
    ],

    /*
    |--------------------------------------------------------------------------
    | Scope Descriptions
    |--------------------------------------------------------------------------
    |
    | Here you may define the descriptions for the scopes that are supported
    | by your authorization server. These descriptions will be displayed
    | to users when they are asked to authorize a client to access their
    | data with a given scope.
    |
    */

    'scope_descriptions' => [
        // 'place-orders' => 'Дозволяє додатку розміщувати нові замовлення від вашого імені.',
        // 'check-status' => 'Дозволяє додатку перевіряти статус ваших замовлень.',
    ],

    /*
    |--------------------------------------------------------------------------
    | правники (Grant Types)
    |--------------------------------------------------------------------------
    |
    | Тут ви можете налаштувати обробники грантів, які підтримує ваш
    | сервер авторизації. Обробники грантів відповідають за обробку
    | різних способів отримання токенів доступу.
    |
    */
    'grant_types' => [
        'authorization_code' => [
            'handler' => \Laravel\Passport\Bridge\AuthorizationCodeGrant::class,
            'revoked_token_check' => \Laravel\Passport\Bridge\RevokedTokenRepository::class,
        ],

        'password' => [
            'handler' => \Laravel\Passport\Bridge\PasswordGrant::class,
            'revoked_token_check' => \Laravel\Passport\Bridge\RevokedTokenRepository::class,
        ],

        'client_credentials' => [
            'handler' => \Laravel\Passport\Bridge\ClientCredentialsGrant::class,
        ],

        'refresh_token' => [
            'handler' => \Laravel\Passport\Bridge\RefreshTokenGrant::class,
            'revoked_token_check' => \Laravel\Passport\Bridge\RevokedTokenRepository::class,
        ],

        'implicit' => [
            'handler' => \Laravel\Passport\Bridge\ImplicitGrant::class,
        ],
    ],

    'keys' => [
        'public' => 'D:\git project\php\php-zstu\Laravel\storage\oauth-public.key',
        'private' => 'D:\git project\php\php-zstu\Laravel\storage\oauth-private.key',
    ],

];
