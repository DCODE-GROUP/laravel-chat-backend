<?php

use Dcodegroup\DCodeChat\Http\Controllers\ChatController;
use Dcodegroup\DCodeChat\Http\Controllers\HeartbeatController;
use Dcodegroup\DCodeChat\Http\Controllers\MessagesController;
use Dcodegroup\DCodeChat\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get(config('dcode-chat.route_path').'/search/', SearchController::class)
        ->name(config('dcode-chat.route_name').'.search');

    Route::resource(config('dcode-chat.route_path').'/chat', ChatController::class)
        ->only(['index', 'show', 'store'])
        ->names([
            'index' => config('dcode-chat.route_name').'.chat.index',
            'show' => config('dcode-chat.route_name').'.chat.show',
            'store' => config('dcode-chat.route_name').'.chat.store',
        ]);

    Route::get(config('dcode-chat.route_path').'/{chat}/messages', [MessagesController::class, 'index'])
        ->name(config('dcode-chat.route_name').'.messages.index');
    Route::post(config('dcode-chat.route_path').'/{chat}/messages', [MessagesController::class, 'store'])
        ->name(config('dcode-chat.route_name').'.messages.store');

    Route::put(config('dcode-chat.route_path').'/{message}', [MessagesController::class, 'update'])
        ->name(config('dcode-chat.route_name').'.messages.update');

    Route::delete(config('dcode-chat.route_path').'/{message}', [MessagesController::class, 'destroy'])
        ->name(config('dcode-chat.route_name').'.messages.destroy');

    Route::get(config('dcode-chat.route_path').'/heartbeat', HeartbeatController::class)->name(config('dcode-chat.route_name').'.heartbeat');
});
