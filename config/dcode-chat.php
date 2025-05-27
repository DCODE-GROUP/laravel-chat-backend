<?php

use App\Models\User;
use Dcodegroup\DCodeChat\Models\Chat;

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
     | eg '/dcode-chat'
     |
     | What should the route name for the chat be
     | eg 'api.dcode-chat',
     */

    'route_path' => env('DCODE_CHAT_ROUTE_PATH', 'dcode-chat'),
    'route_name' => env('DCODE_CHAT_LOG_ROUTE_NAME', 'dcode-chat'),

    /*
    |--------------------------------------------------------------------------
    | Model and Binding
    |--------------------------------------------------------------------------
    |
    | binding - eg 'activity-logs'
    | model - eg 'ActivityLog'
    |
    */

    'binding' => env('DCODE_CHAT_MODEL_BINDING', 'dcode-chat'),
    'dcode_chat_model' => Chat::class,

    /*
     |--------------------------------------------------------------------------
     | Formatting
     |--------------------------------------------------------------------------
     |
     | Configuration here is for display configuration
     |
    */

    'datetime_format' => env('DCODE_CHAT_DATETIME_FORMAT', 'j M Y H:ia'),
    'date_format' => env('DCODE_CHAT_DATE_FORMAT', 'j.m.Y'),

    /*
     |--------------------------------------------------------------------------
     | User
     |--------------------------------------------------------------------------
     |
     | Configuration here is for the user model and table
     | eg 'User'
    */

    'user_model' => User::class,
    'user_table' => env('DCODE_CHAT_USERS_TABLE', 'users'),
    'user_id_field_type' => env('DCODE_CHAT_USERS_ID_FIELD_TYPE', 'unsignedBigInteger'),

];
