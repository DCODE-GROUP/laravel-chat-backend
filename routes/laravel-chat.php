<?php

use Dcodegroup\LaravelChat\Http\Controllers\HeaderController;
use Dcodegroup\LaravelChat\Http\Controllers\MessagesController;
use Dcodegroup\LaravelChat\Http\Controllers\SearchController;

Route::get('/'.config('laravel-chat.route_path').'/header/{chat}', HeaderController::class)
    ->name(config('laravel-chat.route_name').'.header');

Route::get('/'.config('laravel-chat.route_path').'/search/', SearchController::class)
    ->name(config('laravel-chat.route_name').'.search');

Route::get('/'.config('laravel-chat.route_path').'/messages/{message}', [MessagesController::class, 'index'])
    ->name(config('laravel-chat.route_name').'.messages.index');
Route::post('/'.config('laravel-chat.route_path').'/messages/{message}', [MessagesController::class, 'store'])
    ->name(config('laravel-chat.route_name').'.messages.store');
