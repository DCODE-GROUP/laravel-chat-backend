<?php

use App\Models\User;
use Dcodegroup\LaravelChat\Models\Chat;

return [
    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | What middleware should the package apply.
    |
    */

    //    'middleware' => ['web', 'auth'],
    'middleware' => ['web'],

    /*
     |--------------------------------------------------------------------------
     | Routing
     |--------------------------------------------------------------------------
     |
     | Here you can configure the route paths and route name variables.
     |
     | What should the route path for the chat be
     | eg '/laravel-chat'
     |
     | What should the route name for the chat be
     | eg 'api.laravel-chat',
     */

    'route_path' => env('LARAVEL_CHAT_ROUTE_PATH', 'laravel-chat'),
    'route_name' => env('LARAVEL_CHAT_LOG_ROUTE_NAME', 'laravel-chat'),

    /*
    |--------------------------------------------------------------------------
    | Model and Binding
    |--------------------------------------------------------------------------
    |
    | binding - eg 'activity-logs'
    | model - eg 'ActivityLog'
    |
    */

    'binding' => env('LARAVEL_CHAT_MODEL_BINDING', 'laravel-chat'),
    'laravel_chat_model' => Chat::class,

    /*
     |--------------------------------------------------------------------------
     | Formatting
     |--------------------------------------------------------------------------
     |
     | Configuration here is for display configuration
     |
    */

    'datetime_format' => env('LARAVEL_CHAT_DATETIME_FORMAT', 'j M Y H:ia'),
    'date_format' => env('LARAVEL_CHAT_DATE_FORMAT', 'j.m.Y'),

    /*
     |--------------------------------------------------------------------------
     | User
     |--------------------------------------------------------------------------
     |
     | Configuration here is for the user model and table
     | eg 'User'
    */

    'user_model' => User::class,
    'user_table' => env('LARAVEL_CHAT_USERS_TABLE', 'users'),
    'user_id_field_type' => env('LARAVEL_CHAT_USERS_ID_FIELD_TYPE', 'unsignedBigInteger'),

];
