<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SSO
    |--------------------------------------------------------------------------
    |
    | https://laravel.com/docs/10.x/passport
    |
    */

    'host_url' => env('SSO_HOST_URL'),

    'client_id' => env('SSO_CLIENT_ID'),

    'client_secret' => env('SSO_CLIENT_SECRET'),

    'client_redirect_url' => env('SSO_CLIENT_REDIRECT_URL'),

];
