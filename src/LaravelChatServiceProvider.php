<?php

namespace Dcodegroup\LaravelChat;

use Dcodegroup\LaravelChat\Commands\InstallCommand;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class LaravelChatServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->offerPublishing();
        $this->registerCommands();
        $this->setupMigrations();
        $this->registerRoutes();

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'laravel-chat-translations');
    }

    protected function offerPublishing(): void
    {
        $this->setupMigrations();

        $this->publishes([__DIR__.'/../config/laravel-chat.php' => config_path('laravel-chat.php')], 'laravel-chat-config');
        $this->publishes([__DIR__.'/../lang' => $this->app->langPath()], 'laravel-chat-translations');
    }

    protected function setupMigrations(): void
    {
        if ($this->app->environment('local')) {
            if (! Schema::hasTable('chats')) {
                $this->publishes([
                    __DIR__.'/../database/migrations/create_chats_tables.stub.php' => $this->app->databasePath('migrations/'.date('Y_m_d_His', time()).'_create_chats_tables.php'),
                ], 'laravel-chat-migrations');
            }
        }
    }

    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }

    protected function registerRoutes()
    {
        Route::group([
            'middleware' => config('laravel-chat.middleware', 'web'),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/laravel-chat.php');
        });
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-chat.php', 'laravel-chat');
    }
}
