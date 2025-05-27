<?php

namespace Dcodegroup\DCodeChat;

use Dcodegroup\DCodeChat\Commands\InstallCommand;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class DCodeChatServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->offerPublishing();
        $this->registerCommands();
        $this->setupMigrations();
        $this->registerRoutes();

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'dcode-chat-translations');

        View::composer('*', function ($view) {
            $view->with('ziggy', app('router')->getRoutes());
        });
    }

    protected function offerPublishing(): void
    {
        $this->setupMigrations();

        $this->publishes([__DIR__.'/../config/dcode-chat.php' => config_path('dcode-chat.php')], 'dcode-chat-config');
        $this->publishes([__DIR__.'/../lang' => $this->app->langPath()], 'dcode-chat-translations');
    }

    protected function setupMigrations(): void
    {
        if (! Schema::hasTable('chats')) {
            // Chat migrations
            collect([
                'create_chats_table.stub.php',
                'create_chat_messages_table.stub.php',
                'create_chat_users_table.stub.php',
            ])->each(function ($migration, $index) {
                $newPath = database_path('migrations/'.date('Y_m_d_His').'_'.$index.'_'.str_replace('.stub', '', $migration));
                $this->publishes([
                    __DIR__.'/../database/migrations/'.$migration => $newPath,
                ], 'dcode-chat-migrations');
            });
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
            'middleware' => config('dcode-chat.middleware', 'web'),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/dcode-chat.php');
        });
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/dcode-chat.php', 'dcode-chat');
    }
}
